<?php

namespace partner\models;

use Yii;
use common\models\Partner;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
               // 'filter' => ['status' => Partner::STATUS_INACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = Partner::findOne([
            'email' => $this->email,
            //'partner_status' => Partner::STATUS_INACTIVE
        ]);

        if ($user === null) {

            Yii::$app->session->setFlash('error', "No account with provided mail");

            return false;
        }

        //Check if this user sent an email in past few minutes (to limit email spam)
        $emailLimitDatetime = new \DateTime($partner->partner_limit_email);
        date_add($emailLimitDatetime, date_interval_create_from_date_string('1 minutes'));
        $currentDatetime = new \DateTime();

        if ($partner->partner_limit_email && $currentDatetime < $emailLimitDatetime) {

            $difference = $currentDatetime->diff($emailLimitDatetime);
            $minuteDifference = (int) $difference->i;
            $secondDifference = (int) $difference->s;

            $errors = Yii::t('api', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                'numMinutes' => $minuteDifference,
                'numSeconds' => $secondDifference,
            ]);

            Yii::$app->session->setFlash('error', $errors);

            return false;
        }

        $mailer = Yii::$app->mailer->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name);

        if(\Yii::$app->params['elasticMailIpPool'])
            $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

        return $mailer->send();
    }
}
