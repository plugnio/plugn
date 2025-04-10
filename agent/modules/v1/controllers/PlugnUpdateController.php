<?php

namespace agent\modules\v1\controllers;

use common\models\PlugnUpdates;
use yii\data\ActiveDataProvider;

class PlugnUpdateController extends BaseController
{
    /**
     * @return ActiveDataProvider
     * 
     * @api {get} /plugn-updates Get plugn updates
     * @apiName GetPlugnUpdates
     * @apiGroup PlugnUpdate
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionIndex() {

        $query =  PlugnUpdates::find()
            ->orderBy('created_at DESC');

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}