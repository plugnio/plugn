<?php

namespace frontend\models;

use yii;
use borales\extensions\phoneInput\PhoneInputValidator;

class Restaurant extends \common\models\Restaurant {

  public function rules() {
      return array_merge(parent::rules(), [
        [['owner_number'], PhoneInputValidator::className(), 'message' => 'Please insert a valid phone number', 'on' => [self::SCENARIO_CREATE_TAP_ACCOUNT, self::SCENARIO_CREATE_MYFATOORAH_ACCOUNT ,self::SCENARIO_CREATE_STORE_BY_AGENT]],
        [['phone_number'], PhoneInputValidator::className(), 'message' => 'Please insert a valid phone number']
      ]);
  }
}
