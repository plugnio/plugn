<?php


namespace agent\models;


class Area extends \common\models\Area
{
    public function extraFields()
    {
        $fields = parent::fields();

        return $fields;
    }
}