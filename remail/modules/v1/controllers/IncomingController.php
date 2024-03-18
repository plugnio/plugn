<?php

namespace remail\modules\v1\controllers;

use agent\models\Agent;
use agent\models\AgentAssignment;
use common\models\Ticket;
use common\models\TicketComment;
use crm\models\Staff;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Email;
use common\models\EmailAttachment;

/**
 * Incoming controller
 * For management of incoming mail from SendGrid Incoming Parse Webhook
 */
class IncomingController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * https://help.elasticemail.com/en/articles/4804685-notification-settings
     */
    public function actionReceive() {

        $from_email = Yii::$app->request->getBodyParam("from_email");
        $subject = Yii::$app->request->getBodyParam("subject");
        $body_html = TicketComment::extractMessageFromEmail(Yii::$app->request->getBodyParam("body_html")); //
        $body_text = Yii::$app->request->getBodyParam("body_text");

        //env_to_list (list of envelope to addresses - RCPT TO, separated by CRLF)
        
        //to_list (list of email addresses the email was sent to separated by /r/n)

        //att1_name=attachment_file_name&att1_content=encoded_to_base64_binary_data
        //att2_name=attachment_file_name&att2_content=encoded_to_base64_binary_data

        $ticket_uuid = substr($subject, 1);

        $ticket = Ticket::find()
            ->andWhere(['ticket_uuid' => $ticket_uuid])
            ->one();

        if(!$ticket) {

            // Send email back to sender telling him that email is dropped

            Yii::$app->mailer->htmlLayout = "layouts/html";

            Yii::$app->mailer->compose([
                'html' => 'remail/email-dropped-ticket-not-found-html',
                'text' => 'remail/email-dropped-ticket-not-found-text',
            ], [
                'emailText' => $body_text,
                'ticket_uuid' => $ticket_uuid
            ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
                ->setTo($from_email)
                ->setSubject("Your message wasn't delivered")
                ->send();

            // Return error message
            $errorMessage = "Ticket not found";

            // Yii::warning("[Remailer] ".$errorMessage, __METHOD__);
            return [
                'operation' => 'error',
                'message' => $errorMessage
            ];
        }

        $agent = Agent::find()
            ->andWhere(['agent_email' => $from_email])
            ->one();

        $staff = Staff::find()
            ->andWhere(['staff_email' => $from_email])
            ->one();

        $comment = new TicketComment();
        $comment->ticket_uuid = $ticket_uuid;
        $comment->agent_id = $agent? $agent->agent_uuid: null;
        $comment->staff_id = $staff? $staff->staff_id: null;
        $comment->ticket_comment_detail = $body_html;

        // authorisation of agent for ticket access

        $havingAccess = true;

        if($agent) {
            $agentAccess = AgentAssignment::find()
                ->andWhere(['restaurant_uuid' => $ticket->restaurant_uuid, "agent_uuid" => $agent->agent_uuid])
                ->one();

            if(!$agentAccess) {
                $havingAccess = false;
            }
        }

        // In case we didnt find sender
        if (!$havingAccess || (!$comment->agent_id && !$comment->staff_id)) {

            // Send email back to sender telling him that email is dropped

            Yii::$app->mailer->htmlLayout = "layouts/html";

            Yii::$app->mailer->compose([
                        'html' => 'remail/email-dropped-unauthorized-html',
                        'text' => 'remail/email-dropped-unauthorized-text',
                            ], [
                        'emailFrom' => $from_email,
                        'ticket_uuid' => $ticket_uuid,
                        'emailText' => $body_text
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
                    ->setTo($email->email_from)
                    ->setSubject("Your message wasn't delivered")
                    ->send();

            // Return error message
            $errorMessage = 'You do not have access to this conversation';
            // Yii::warning("[Remailer] ".$errorMessage, __METHOD__);

            return [
                'operation' => 'error',
                'message' => $errorMessage
            ];
        }

        // Attempt to save email record
        if (!$comment->save()) {
            Yii::error("[Remailer] " . print_r($comment->getErrors(), true), __METHOD__);
            return [
                'operation' => 'error',
                'message' => $comment->getErrors()
            ];
        }

        // If attachments available, start processing them
        /*if ($email->email_num_attachments > 0) {
            // Set email number of attachments to zero, and increment for each successful upload
            $email->email_num_attachments = 0;

            // Loop through attachment info and store in S3
            $attachmentInfo = Yii::$app->request->getBodyParam("attachment-info");
            $attachmentInfo = json_decode($attachmentInfo, true);
            /**
             * [attachment-info] =>
             * {
             * "attachment2":
             *  {"filename":"Screen Shot 2018-09-03 at 11.42.59 PM.png","name":"Screen Shot 2018-09-03 at 11.42.59 PM.png","type":"image/png","content-id":"f_jmdvm75o0"},
             * "attachment1":
             *  {"filename":"Screen Shot 2018-09-03 at 11.42.59 PM.png","name":"Screen Shot 2018-09-03 at 11.42.59 PM.png","type":"image/png","content-id":"ii_jmdvmiv31"}
             *  }
             *
            foreach ($attachmentInfo as $key => $info) {
                
                $fileToUpload = \yii\web\UploadedFile::getInstanceByName($key);

                // path format: attachment/employer-uuid/conversation-uuid/filename.ext
                $storeInS3FolderPath = $conversation->employer_uuid . "/" . $conversation->conversation_uuid;

                try {
                    $email->uploadAttachment($fileToUpload, $info, $storeInS3FolderPath);
                } catch (\Exception $e) {
                    Yii::error("[Remailer] " . $e->getMessage(), __METHOD__);
                    return [
                        'operation' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }
        }*/

        return [
            "operation" => "success",
            "message" => "Works"
        ];
    }

    /**
     * Process received data from SendGrid parse Webhook
     * Docs: https://sendgrid.com/docs/for-developers/parsing-email/setting-up-the-inbound-parse-webhook/
     *
     * Dev Server:
     * Test by sending email to: <conversation-uuid>@remail.dev.pogi.io
     *
     * Prod Server:
     * Test by sending email to: <conversation-uuid>@remail.pogi.io
     *
    public function actionReceiveFromSendGrid() {
        $email = new Email();

        // Recipient of the email
        $email->email_to = Yii::$app->request->getBodyParam("to");
        // Za36o6 alz36o6 via Pogi <conversation-4c16ed1d-eb2e-11e8-8a6f-0243ef891682@remail.dev.pogi.io>
        // Who sent the email?
        $email->email_from = Yii::$app->request->getBodyParam("from");
        //original agent or candidate email  

        $email->email_from_ip = Yii::$app->request->getBodyParam("sender_ip"); // 209.85.210.178
        // Extract email from sender string and recipient string
        $email->email_from = Email::extractEmailFromString($email->email_from);
        $email->email_to = Email::extractEmailFromString($email->email_to);

        // Contents of the email (Remove reply content from original email text)
        $email->email_text = Email::extractMessageFromEmail(Yii::$app->request->getBodyParam("text"));

        // Check if conversation referenced in mail exists, otherwise skip this email
        $conversationUuid = explode("@", $email->email_to)[0];
        $conversation = \common\models\Conversation::find()
                ->where(['conversation_uuid' => $conversationUuid])
                ->with([
                    'candidate',
                    'employer',
                    'employer.employerAgents',
                    'employer.employerAgents.agent'
                ])
                ->one();

        // If no conversation found, send email message and return error
        if (!$conversation) {
            // Send email back to sender telling him that email is dropped
            $defaultMailLayout = Yii::$app->mailer->htmlLayout;
            Yii::$app->mailer->htmlLayout = "layouts/html";
            Yii::$app->mailer->compose([
                        'html' => 'remail/email-dropped-conv-not-found-html',
                        'text' => 'remail/email-dropped-conv-not-found-text',
                            ], [
                        'emailText' => $email->email_text,
                        'conversationUuid' => $conversationUuid
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
                    ->setTo($email->email_from)
                    ->setSubject("Your message wasn't delivered")
                    ->send();
            Yii::$app->mailer->htmlLayout = $defaultMailLayout;

            // Return error message
            $errorMessage = "Conversation not found";
            // Yii::warning("[Remailer] ".$errorMessage, __METHOD__);
            return [
                'operation' => 'error',
                'message' => $errorMessage
            ];
        }

        // Set email as child of this conversation
        $email->conversation_uuid = $conversation->conversation_uuid;

        // Test for MAJOR issue where conversation references employer/candidate uuid that dont exist in our db
        if (!$conversation->candidate || !$conversation->employer) {
            $errorMessage = '[Remailer] Corrupt conversation record. Employer or candidate belonging to it is missing';
            Yii::error($errorMessage, __METHOD__);
            return [
                'operation' => 'error',
                'message' => $errorMessage
            ];
        }

        // Is this email from the candidate belonging to conversation?
        $senderFound = false;
        if ($conversation->candidate && $conversation->candidate->email == $email->email_from) {
            $senderFound = true;
            $email->candidate_uuid = $conversation->candidate_uuid;
            $email->email_sender_type = Email::SENDER_CANDIDATE;
        } else {
            // Check that this email is from agent belonging employer from this conversation
            $agentsAllowedAccessToConversation = $conversation->employer->employerAgents;
            foreach ($agentsAllowedAccessToConversation as $agentAssignment) {
                $agent = $agentAssignment->agent;

                if ($agent->email == $email->email_from) {
                    $senderFound = true;
                    $email->agent_uuid = $agent->agent_uuid;
                    $email->email_sender_type = Email::SENDER_AGENT;
                    break;
                }
            }
        }

        // In case we didnt find sender within conversation candidate or its agents
        if (!$senderFound) {
            // Send email back to sender telling him that email is dropped
            $defaultMailLayout = Yii::$app->mailer->htmlLayout;
            Yii::$app->mailer->htmlLayout = "layouts/html";
            Yii::$app->mailer->compose([
                        'html' => 'remail/email-dropped-unauthorized-html',
                        'text' => 'remail/email-dropped-unauthorized-text',
                            ], [
                        'emailFrom' => $email->email_from,
                        'conversationUuid' => $conversationUuid,
                        'emailText' => $email->email_text
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
                    ->setTo($email->email_from)
                    ->setSubject("Your message wasn't delivered")
                    ->send();
            Yii::$app->mailer->htmlLayout = $defaultMailLayout;

            // Return error message
            $errorMessage = 'You do not have access to this conversation';
            // Yii::warning("[Remailer] ".$errorMessage, __METHOD__);
            return [
                'operation' => 'error',
                'message' => $errorMessage
            ];
        }

        // Number of attachments
        $email->email_num_attachments = (int) Yii::$app->request->getBodyParam("attachments");

        // Source of email is from Sendgrid incoming webhook
        $email->email_creation_source = Email::SOURCE_SENDGRID_INCOMING;

        // Email requires processing and re-sending to second party
        $email->is_mailed_to_employer = Email::PROCESSING_REQUIRED;
        $email->is_mailed_to_employer = Email::PROCESSING_REQUIRED;

        // All body params stored in email_contents for analyzing later if facing issues
        $email->email_contents = print_r(Yii::$app->request->bodyParams, true);

        // Base filesize is zero. We will append to that as we process attachment records
        $email->email_attachments_filesize = 0;

        // Start a Transaction for Saving the email record along with all its attachment records
        $transaction = Yii::$app->db->beginTransaction();

        // Attempt to save email record
        if (!$email->save()) {
            Yii::error("[Remailer] " . print_r($email->getErrors(), true), __METHOD__);
            return [
                'operation' => 'error',
                'message' => $email->getErrors()
            ];
        }

        // If attachments available, start processing them
        if ($email->email_num_attachments > 0) {
            // Set email number of attachments to zero, and increment for each successful upload
            $email->email_num_attachments = 0;

            // Loop through attachment info and store in S3
            $attachmentInfo = Yii::$app->request->getBodyParam("attachment-info");
            $attachmentInfo = json_decode($attachmentInfo, true);
            /**
             * [attachment-info] =>
             * {
             * "attachment2":
             *  {"filename":"Screen Shot 2018-09-03 at 11.42.59 PM.png","name":"Screen Shot 2018-09-03 at 11.42.59 PM.png","type":"image/png","content-id":"f_jmdvm75o0"},
             * "attachment1":
             *  {"filename":"Screen Shot 2018-09-03 at 11.42.59 PM.png","name":"Screen Shot 2018-09-03 at 11.42.59 PM.png","type":"image/png","content-id":"ii_jmdvmiv31"}
             *  }
             *
            foreach ($attachmentInfo as $key => $info) {
                
                $fileToUpload = \yii\web\UploadedFile::getInstanceByName($key);

                // path format: attachment/employer-uuid/conversation-uuid/filename.ext
                $storeInS3FolderPath = $conversation->employer_uuid . "/" . $conversation->conversation_uuid;

                try {
                    $email->uploadAttachment($fileToUpload, $info, $storeInS3FolderPath);
                } catch (\Exception $e) {
                    Yii::error("[Remailer] " . $e->getMessage(), __METHOD__);
                    return [
                        'operation' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }
        }

        // Commit the transaction saving the email record along with its attachments.
        $transaction->commit();


//        // Process email sending to agents/candidate.
//        // TODO: Later refactor this into its own service/background task with SNS/SQS
//        $email->processEmail();

        return [
            "operation" => "success",
            "message" => "Works"
        ];
    }*/
}
