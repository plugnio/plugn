<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use agent\models\Bank;

class BankController extends BaseController {

    /**
     * @param $store_uuid
     * @return ActiveDataProvider
     * 
     * @api {get} /bank Get list of banks
     * @apiName ListBanks
     * @apiGroup Bank
     *
     * @apiSuccess {Array} banks List of banks.
     */
    public function actionList($store_uuid = null) {

          Yii::$app->accountManager->getManagedAccount($store_uuid);

          $banks =  Bank::find();

          return new ActiveDataProvider([
            'query' => $banks
          ]);
    }
}
