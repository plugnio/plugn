<?php

namespace agent\models;

use common\models\MailLog;
use Yii;
use yii\base\Model;
use common\models\Agent;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Agent',
                'targetAttribute' => 'agent_email',
                'filter' => ['agent_status' => Agent::STATUS_ACTIVE, 'deleted' => 0],
                'message' => 'There is no agent with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail($agent = null) {

      if (!$agent) {
          $agent = Agent::findOne([
              'agent_email' => $this->email,
          ]);
      }

      if (!$agent) {
          return false;
      }

        if (!Agent::isPasswordResetTokenValid($agent->agent_password_reset_token)) {
            $agent->generatePasswordResetToken();
        }

        if ($agent->save(false)) {

            $ml = new MailLog();
            $ml->to = $this->email;
            $ml->from = Yii::$app->params['noReplyEmail'];
            $ml->subject = 'Password reset for ' . Yii::$app->name . ' Dashboard';
            $ml->save();

            $resetLink = Yii::$app->params['newDashboardAppUrl'] . '/update-password/' . $agent->agent_password_reset_token;

            $mailer = Yii::$app->mailer->compose([
                'html' => 'passwordResetToken-html',
                'text' => 'passwordResetToken-text'
            ], [
                    'resetLink' => $resetLink,
                    'agent' => $agent
                ]
            )
                ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->name])
                ->setReplyTo(\Yii::$app->params['supportEmail'])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . Yii::$app->name . ' Dashboard');

            $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

            try {
                return $mailer->send();
            } catch (\Swift_TransportException $e) {
                Yii::error($e->getMessage(), "email");
            }
        }

      return true;
   }


}
