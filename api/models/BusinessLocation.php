<?php


namespace api\models;


class BusinessLocation extends \common\models\BusinessLocation
{

    /**
   * @inheritdoc
   */
  public function fields()
  {
      $fields = parent::fields ();

      // remove fields that contain sensitive information
      unset($fields['mashkor_branch_id']);
      unset($fields['armada_api_key']);

      return $fields;

  }
}
