<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Customer;

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
                'targetClass' => '\common\models\Customer',
                'targetAttribute' => 'customer_email',
                //'filter' => ['deleted' => 0],
                'message' => 'There is no customer with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail($customer = null) {

        if (!$customer) {
            $customer = Customer::findOne([
                'customer_email' => $this->email,
            ]);
        }

        if ($customer) {
            if (!Customer::isPasswordResetTokenValid($customer->customer_password_reset_token)) {
                $customer->generatePasswordResetToken();
            }

            if ($customer->save(false)) {

                //todo: replace with store link
                $resetLink = Yii::$app->params['newDashboardAppUrl'] . '/update-password/' . $customer->customer_password_reset_token;

                $mailer = Yii::$app->mailer
                    ->compose(
                        [
                            'html' => 'customer/passwordResetToken-html',
                            'text' => 'customer/passwordResetToken-text'
                        ], [
                            'resetLink' => $resetLink,
                            'customer' => $customer
                        ]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . Yii::$app->name . ' Dashboard');

                try {
                    $mailer->send();
                } catch (\Swift_TransportException $e) {
                    Yii::error($e->getMessage(), "email");
                }
            }
        }
    }
}
