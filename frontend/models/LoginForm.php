<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Agent;

/**
 * Login form
 */
class LoginForm extends Model {

    public $email;
    public $password;
    public $restaurantUuid;
    public $ownedRestaurant;
    public $rememberMe = true;
    private $_agent = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // email , password and restaurantUuid are both required
            [['email', 'password', 'restaurantUuid'], 'required'],
            // email must be an email
            ['email', 'email'],
            // restaurantUuid must be a string
            [['restaurantUuid'], 'string', 'max' => 60],
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
            $agent = $this->getAgent();
            if (!$agent || !$agent->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a agent using the provided email and password.
     *
     * @return boolean whether the agent is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $loggedInAgent = $this->getAgent();
            if ($loggedInAgent != NULL) {
                Yii::$app->user->login($loggedInAgent, $this->rememberMe ? 3600 * 24 * 30 : 0);
                return Yii::$app->ownedAccountManager->getOwnedAccount($this->restaurantUuid);
            }
        }

        return false;
    }

    /**
     * Finds agent by [[username]]
     *
     * @return Agent|null
     */
    public function getAgent() {
        if ($this->_agent === false) {
            $this->_agent = Agent::findByEmail($this->email);
        }

        return $this->_agent;
    }

}
