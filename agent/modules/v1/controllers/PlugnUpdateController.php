<?php

namespace agent\modules\v1\controllers;

use common\models\PlugnUpdates;
use yii\data\ActiveDataProvider;

class PlugnUpdateController extends BaseController
{
    /**
     * @return ActiveDataProvider
     */
    public function actionIndex() {

        $query =  PlugnUpdates::find()
            ->orderBy('created_at DESC');

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}