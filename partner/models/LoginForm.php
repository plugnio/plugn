<?php

namespace partner\models;

use Yii;
use yii\base\Model;
use common\models\Partner;

/**
 * Login form
 */
class LoginForm extends Model {

    public $email;
    public $password;
    public $managedRestaurant;
    public $rememberMe = true;
    private $_partner = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // email , password are both required
            [['email', 'password'], 'required'],
            // email must be an email
            ['email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $partner = $this->getPartner();
            if (!$partner || !$partner->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getPartner(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds partner by [[username]]
     *
     * @return Partner|null
     */
    public function getPartner() {
        if ($this->_partner === false) {
            $this->_partner = Partner::findByEmail($this->email);
        }

        return $this->_partner;
    }

}
