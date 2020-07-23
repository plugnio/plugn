<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the signup form.
 */
class SignupForm extends Model {

    public $item_uuid;
    public $name;
    public $phone;
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'trim'],
            [['name', 'company_name', 'phone', 'email'], 'required'],
            [['name', 'company_name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'min' => 8, 'max' => 8],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
        ];
    }


    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail() {

        if (!$this->validate()) {
            return null;
        }

        return Yii::$app->mailer
                        ->compose(
                                ['html' => 'signupForm-html', 'text' => 'signupForm-text'], ['model' => $this]
                        )
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo(Yii::$app->params['supportEmail'])
                        ->setSubject('[Plugn] New Agent - ' . $this->name )
                        ->send();
    }

}
