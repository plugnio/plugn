<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;


class DomainRequestController extends BaseController
{
    /**
     * Return list of domain requests
     * 
     * @api {get} /domain-requests Get list of domain requests
     * @apiName GetDomainRequests
     * @apiGroup DomainRequest
     * 
     * @apiSuccess {Array} domainRequests List of domain requests.
     */
    public function actionIndex()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $query = $store->getRestaurantDomainRequests();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}
