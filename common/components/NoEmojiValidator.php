<?php

namespace common\components;

use Yii;
use yii\validators\Validator;

class NoEmojiValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $model->$attribute)) {
            $this->addError($model, $attribute, Yii::t("app",'Emojis are not allowed.'));
            return false;
        }
    }
}
