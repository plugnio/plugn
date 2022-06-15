<?php

namespace partner\models;

use Yii;
use yii\base\Model;
use common\models\Partner;

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
                'targetClass' => '\common\models\Partner',
                'targetAttribute' => 'partner_email',
                'filter' => ['partner_status' => Partner::STATUS_ACTIVE],
                'message' => 'There is no partner with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail() {

        /* @var $partner Partner */
        $partner = Partner::findOne([
                    'partner_status' => Partner::STATUS_ACTIVE,
                    'partner_email' => $this->email,
        ]);

        if (!$partner) {
            return false;
        }

        if (!Partner::isPasswordResetTokenValid($partner->partner_password_reset_token)) {

            $partner->generatePasswordResetToken();

            $partner->setScenario(Partner::SCENARIO_PASSWORD_TOKEN);

            if (!$partner->save()) {

                //die(var_dump($partner->errors));

                return false;
            }
        }


        // return Yii::$app->mailer
        //                 ->compose(
        //                         ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['partner' => $partner]
        //                 )
        //                 ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        //                 ->setTo($this->email)
        //                 ->setSubject('Reset your password')
        //                 ->send();

        
        return true;
    }

}
