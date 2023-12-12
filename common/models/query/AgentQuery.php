<?php

namespace common\models\query;

class AgentQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['agent.deleted' => 0]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['agent.deleted' => 0]);
        return parent::one ($db);
    }
}

