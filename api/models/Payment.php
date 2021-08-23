<?php

namespace api\models;


class Payment extends \common\models\Payment {

  /**
   * @inheritdoc
   */
  public function fields() {
      $fields = parent::fields();

      // remove fields that contain sensitive information
      unset($fields['payment_net_amount']);
      unset($fields['payment_gateway_fee']);
      unset($fields['plugn_fee']);

      return $fields;

  }

}
