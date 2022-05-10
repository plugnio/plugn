<?php
namespace api\components;

use yii\base\BaseObject;


class Currency extends BaseObject
{
    public $code;

    /* @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function setCode($currency) {
        $this->code = $currency;
    }

    public function getCode() {
        return $this->code;
    }
}