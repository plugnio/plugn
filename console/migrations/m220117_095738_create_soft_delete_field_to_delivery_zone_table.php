<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%soft_delete_field_to_delivery_zone}}`.
 */
class m220117_095738_create_soft_delete_field_to_delivery_zone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn ('delivery_zone', 'is_deleted', $this->tinyInteger(1)->defaultValue(0));
      $this->addColumn ('area_delivery_zone', 'is_deleted', $this->tinyInteger(1)->defaultValue(0));

      //$sql = "SELECT * FROM `business_location` where is_deleted = 1;


      $sql = "SELECT * from business_location where business_location.is_deleted = 1";

      $rows = Yii::$app->db->createCommand ($sql)->queryAll ();

      //Yii::$app->db->createCommand ($sql)->queryAll ();

      foreach($rows as $row) {

          \common\models\DeliveryZone::updateAll([
              'is_deleted' => 1
          ], [
              'business_location_id' => $row['business_location_id'],
              'restaurant_uuid' => $row['restaurant_uuid']
          ]);
      }



      $deliveryZoneSql = "SELECT * from delivery_zone where delivery_zone.is_deleted = 1";

      $deliveryZoneRows = Yii::$app->db->createCommand ($deliveryZoneSql)->queryAll ();

      //Yii::$app->db->createCommand ($sql)->queryAll ();

      foreach($deliveryZoneRows as $row) {

          \common\models\AreaDeliveryZone::updateAll([
              'is_deleted' => 1
          ], [
              'delivery_zone_id' => $row['delivery_zone_id'],
              'restaurant_uuid' => $row['restaurant_uuid']
          ]);
      }






    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('delivery_zone', 'is_deleted');
      $this->dropColumn('area_delivery_zone', 'is_deleted');
    }
}
