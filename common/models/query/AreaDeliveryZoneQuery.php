<?php

namespace common\models\query;


/**
 * AreaDeliveryZoneQuery extends ActiveQuery, allowing easier filtering of AreaDeliveryZoneQuery
 */
class AreaDeliveryZoneQuery extends \yii\db\ActiveQuery {

    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['area_delivery_zone.is_deleted' => 0]);

        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['area_delivery_zone.is_deleted' => 0]);

        return parent::one($db);
    }
}
