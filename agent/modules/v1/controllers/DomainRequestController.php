<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;


class DomainRequestController extends BaseController
{
    public function actionIndex()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $query = $store->getRestaurantDomainRequests();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}
