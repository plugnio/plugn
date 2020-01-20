<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Vendor;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_vendor = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
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
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $vendor = $this->getVendor();
            if (!$vendor || !$vendor->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a vendor using the provided email and password.
     *
     * @return boolean whether the vendor is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getVendor(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds vendor by [[username]]
     *
     * @return Vendor|null
     */
    public function getVendor()
    {
        if ($this->_vendor === false) {
            $this->_vendor = Vendor::findByEmail($this->email);
        }

        return $this->_vendor;
    }
}
