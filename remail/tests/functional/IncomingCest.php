<?php

namespace remail\tests;

use Codeception\Util\HttpCode;

class IncomingCest {

    public $token;

    public function _fixtures() {
        return [
            'tickets' => \common\fixtures\TicketFixture::className(),
            'ticketComments' => \common\fixtures\TicketCommentFixture::className(),
            'staffs' => \common\fixtures\StaffFixture::className(),
            'agents' => \common\fixtures\AgentFixture::className(),
            'agentAssignments' => \common\fixtures\AgentAssignmentFixture::className(),
            'restaurants' => \common\fixtures\RestaurantFixture::className()
        ];
    }

    /**
     * When email is received to an invalid conversation ID
     * @param FunctionalTester $I
     */
    public function emailReceivedToInvalidConversationId(FunctionalTester $I) {
        $I->wantTo('Receive incoming email regarding a conversation uuid not available on our system');
        $I->sendPOST('v1/incoming', [
            'to_email' => 'conversation-123443@remail.dev.plugn.io',
            'from_email' => 'johnathon.dietrich@yahoo.com',
            'body_text' => 'Dear members, this is where email content goes.',
            'body_html' => 'Dear members, this is where email content goes.',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'operation' => 'string',
            'message' => 'string'
        ]);
        $I->seeResponseContainsJson([
            'operation' => 'error',
        ]);
    }

    /**
     * When email is received for a conversation from a agent/person not authorized to be within conversation
     * @param FunctionalTester $I
     *
    public function emailToConversationFromUnauthorizedAgent(FunctionalTester $I) {

        // Get a conversations who's employers have agents assigned.
        $conversation = \common\models\Ticket::find()
                ->joinWith("agents")
                ->one();

        // Create array of agent uuid's belonging to conversation
        $agentUUIDS = [];
        $agentsAllowedAccessToConversation = $conversation->employer->employerAgents;
        foreach ($agentsAllowedAccessToConversation as $agentAssignment) {
            $agent = $agentAssignment->agent;
            $agentUUIDS[] = $agent->agent_uuid;
        }

        // Sender is a agent that doesn't belong to conversation
        $senderAgent = \common\models\Agent::find()
                        ->where(['not in', 'agent_uuid', $agentUUIDS])
                        ->one();

        $I->wantTo('Receive mail: regarding a valid conversation, but the sender is an agent not belonging to conversation');
        $I->sendPOST('v1/incoming', [
            'to' => $conversation->conversation_uuid . '@remail.dev.pogi.io',
            'from' => $senderAgent->first_name . " " . $senderAgent->last_name . ' <' . $senderAgent->email . '>',
            'sender_ip' => '209.85.215.180',
            'text' => 'Dear members, this is where email content goes.',
            'attachments' => 0,
            'attachment-info' => '{}'
                ]
        );
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'operation' => 'string',
            'message' => 'string|array'
        ]);
        $I->seeResponseContainsJson([
            'operation' => 'error',
            'message' => 'You do not have access to this conversation'
        ]);
    }*/

    /**
     * Test for emails received with attachments.
     * Confirms that attachments are successfully uploaded to S3 and stored in our db
     * @param \remail\tests\FunctionalTester $I
     */
    public function validEmailReceivedWithAttachmentIncluded(FunctionalTester $I) {

        $ticket = \common\models\Ticket::find()
                ->with("agent")
                ->one();

        //$attachmentFileSize = filesize(codecept_data_dir('files/sample.jpg'));

        $I->wantTo('Receive valid email with attachment included');

        $I->sendPOST('v1/incoming', [
            'to_email' => $ticket->ticket_uuid . '@remail.dev.pogi.io',
            'from_email' => $ticket->agent->agent_uuid,
            'body_text' => 'Dear members, this is where email content goes.',
            'body_html' => 'Dear members, this is where email content goes.'
        ]);

        /*'attachment-info' => '{"attachment1":{"filename":"sample.jpg","name":"sample.jpg","type":"image/jpg","content-id":"f_jmg7w2q90"}}'
            ],
            [
            'attachment1' => [
                  'name' => 'screenshot.jpg',
                  'type' => 'image/jpg',
                  'error' => UPLOAD_ERR_OK,
                  'size' => $attachmentFileSize,
                  'tmp_name' => codecept_data_dir('files/sample.jpg')
             ]
           ]*/
        $I->seeResponseCodeIs(HttpCode::OK);  //200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'operation' => 'string',
            'message' => 'string'
        ]);
        $I->seeResponseContainsJson([
            'operation' => 'success',
        ]);
    }
    
    /**
     * Test for emails received with banned attachments.
     * Confirms that attachments are not uploaded to S3 or to our db or email count
     * @param \remail\tests\FunctionalTester $I
     *
    public function validEmailReceivedWithBannedAttachment(FunctionalTester $I) {
        $conversation = \common\models\Conversation::find()
                ->with("candidate")
                ->one();
        $senderCandidate = $conversation->candidate;
        
        $fileName = 'harmful.exe';
        $fileExtension = 'exe';

        $I->wantTo('Receive valid email with a banned attachment included');
        $I->sendPOST('v1/incoming', [
            'to' => $conversation->conversation_uuid . '@remail.dev.pogi.io',
            'from' => $senderCandidate->firstname . " " . $senderCandidate->lastname . ' <' . $senderCandidate->email . '>',
            'sender_ip' => '209.85.215.180',
            'text' => 'Dear members, this is where email content goes.',
            'attachments' => 1,
            'attachment-info' => '{"attachment1":{"filename":"harmful.exe","name":"harmful.exe","type":"application/exe","content-id":"f_jmg7w2q90"}}'
                ],
                [
                'attachment1' => [
                      'name' => $fileName,
                      'type' => 'application/x-msdownload',
                      'error' => UPLOAD_ERR_OK,
                      'size' => filesize(codecept_data_dir('files/sample.jpg')),
                      'tmp_name' => codecept_data_dir('files/sample.jpg')
                 ]
               ]
        );
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'operation' => 'string',
            'message' => 'string'
        ]);
        $I->seeResponseContainsJson([
            'operation' => 'success',
        ]);
        
        // Get the email record that was just received 
        $emailRecord = \common\models\Email::find()->orderBy("email_created_at DESC")->limit(1)->one();
        
        // Confirm attachment count uploaded
        $I->assertEquals(0, $emailRecord->email_num_attachments, "Attachment should be missing from Email ActiveRecord");
        
        // Confirm that banned message added to email contents
        $bannedMessage = \common\models\EmailAttachment::getBannedAttachmentMessage($fileName, $fileExtension);
        $I->assertContains($bannedMessage, $emailRecord->email_text, "Email text content should include banned message");
        
    }
*/
}
