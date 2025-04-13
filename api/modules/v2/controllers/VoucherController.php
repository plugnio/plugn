<?php

namespace api\modules\v2\controllers;

use Yii;
use common\models\Restaurant;
use common\models\Voucher;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class VoucherController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count',
                    'Mixpanel-Distinct-ID'
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * Return list of vouchers
     * 
     * @api {GET} /vouchers List of vouchers
     * @apiName ListOfVouchers
     * @apiGroup Voucher
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionList() {

        $store = $this->findStore();

        $query = $store->getVouchers()
            ->orderBy('voucher_created_at DESC')
            ->where([
                'is_public' => 1,
                'voucher_status' => Voucher::VOUCHER_STATUS_ACTIVE
            ]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStore($id = null)
    {
        if(!$id)
            $id = Yii::$app->request->getHeaders()->get('Store-Id');

        $model = Restaurant::findOne($id);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
