<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Customer;
use yii\db\Expression;

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
    public function sendEmail($customer = null, $restaurant = null) {

        if (!$customer) {
            $customer = Customer::findOne([
                'customer_email' => $this->email,
            ]);
        }

        if ($customer) {

            if (!Customer::isPasswordResetTokenValid($customer->customer_password_reset_token)) {
                $customer->generatePasswordResetToken();
            }

            //$customer->customer_limit_email = new Expression('NOW()');

            if ($customer->save(false)) {

                Customer::updateAll([
                    'customer_limit_email' => new Expression('NOW()')
                ], [
                    "customer_id" => $customer->customer_id
                ]);

                if($restaurant) {
                    $resetLink = $restaurant->restaurant_domain . '/update-password/' . $customer->customer_password_reset_token;
                } else {
                    $resetLink = Yii::$app->params['newDashboardAppUrl'] . '/update-password/' . $customer->customer_password_reset_token;
                }

                $mailer = Yii::$app->mailer
                    ->compose(
                        [
                            'html' => 'customer/passwordResetToken-html',
                            'text' => 'customer/passwordResetToken-text'
                        ], [
                            'resetLink' => $resetLink,
                            'customer' => $customer,
                            'restaurant' => $restaurant
                        ]
                    )
                    ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . Yii::$app->name . ' Dashboard');

                try {
                    return $mailer->send();
                } catch (\Swift_TransportException $e) {
                    Yii::error($e->getMessage(), "email");
                }
            }
        }
    }
}
