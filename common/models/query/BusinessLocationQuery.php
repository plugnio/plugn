<?php

namespace common\models\query;


/**
 * BusinessLocationQuery extends ActiveQuery, allowing easier filtering of BusinessLocation
 */
class BusinessLocationQuery extends \yii\db\ActiveQuery {

    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['is_deleted' => 0]);

        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}