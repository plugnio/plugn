<?php

namespace api\models;


class Restaurant extends \common\models\Restaurant {

  /**
   * @param bool $insert
   * @return bool|void
   */
  public function fields() {
      $fields = parent::fields();

      // remove fields that contain sensitive information
      unset($fields['restaurant_email_notification']);
      unset($fields['developer_id']);
      unset($fields['site_id']);
      unset($fields['retention_email_sent']);
      unset($fields['hide_request_driver_button']);
      unset($fields['platform_fee']);
      unset($fields['warehouse_fee']);
      unset($fields['warehouse_delivery_charges']);
      unset($fields['store_branch_name']);
      unset($fields['armada_api_key']);
      unset($fields['mashkor_branch_id']);
      unset($fields['app_id']);
     // unset($fields['restaurant_status']);
      unset($fields['vendor_sector']);
      unset($fields['business_id']);
      unset($fields['business_entity_id']);
      unset($fields['wallet_id']);
      unset($fields['merchant_id']);
      unset($fields['operator_id']);
      unset($fields['supplierCode']);
      unset($fields['live_api_key']);
      unset($fields['test_api_key']);
      //unset($fields['test_public_key']);
      unset($fields['sitemap_require_update']);
      unset($fields['business_type']);
      unset($fields['restaurant_email']);
      unset($fields['license_number']);
      unset($fields['not_for_profit']);
      unset($fields['authorized_signature_issuing_date']);
      unset($fields['authorized_signature_issuing_date']);
      unset($fields['authorized_signature_expiry_date']);
      unset($fields['authorized_signature_title']);
      unset($fields['authorized_signature_file']);
      unset($fields['authorized_signature_file_id']);
      unset($fields['authorized_signature_file_purpose']);
      unset($fields['commercial_license_issuing_date']);
      unset($fields['commercial_license_issuing_date']);
      unset($fields['commercial_license_expiry_date']);
      unset($fields['commercial_license_title']);
      unset($fields['commercial_license_file']);
      unset($fields['commercial_license_file_id']);
      unset($fields['commercial_license_file_purpose']);
      unset($fields['iban']);
      unset($fields['owner_first_name']);
      unset($fields['owner_last_name']);
      unset($fields['owner_email']);
      unset($fields['owner_number']);
      unset($fields['has_deployed']);
      unset($fields['payment_gateway_queue_id']);
      unset($fields['tap_queue_id']);
      //unset($fields['is_tap_enable']);
      //unset($fields['is_myfatoorah_enable']);
      unset($fields['company_name']);
      unset($fields['owner_phone_country_code']);
      unset($fields['identification_issuing_date']);
      unset($fields['identification_expiry_date']);
      unset($fields['identification_file_front_side']);
      unset($fields['identification_file_back_side']);
      unset($fields['identification_file_id_front_side']);
      unset($fields['identification_file_id_back_side']);
      unset($fields['identification_title']);
      unset($fields['identification_file_purpose']);
      unset($fields['restaurant_created_at']);
      unset($fields['restaurant_updated_at']);
      unset($fields['referral_code']);
      //unset($fields['live_public_key']);

      return $fields;
  }

}
