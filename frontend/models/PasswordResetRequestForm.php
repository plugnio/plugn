<?php

namespace frontend\models;

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
                'filter' => ['agent_status' => Agent::STATUS_ACTIVE],
                'message' => 'There is no agent with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail() {
        /* @var $agent Agent */
        $agent = Agent::findOne([
                    'agent_status' => Agent::STATUS_ACTIVE,
                    'agent_email' => $this->email,
        ]);

        if (!$agent) {
            return false;
        }

        if (!Agent::isPasswordResetTokenValid($agent->agent_password_reset_token)) {
            $agent->generatePasswordResetToken();
            if (!$agent->save()) {
                return false;
            }
        }

        return Yii::$app
                        ->mailer
                        ->compose(
                                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['agent' => $agent]
                        )
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo($this->email)
                        ->setSubject('Password reset for ' . Yii::$app->name)
                        ->send();
    }

}
