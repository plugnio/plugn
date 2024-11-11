<?php

namespace agent\modules\v1\controllers\payment;

use agent\models\Restaurant;
use agent\modules\v1\controllers\BaseController;
use common\models\TabbyTransaction;
use yii\web\NotFoundHttpException;

class TabbyController extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'callback'];

        return $behaviors;
    }

    public function actionInstall() {
        $store = $this->findStore();

        TabbyTransaction::registerWebhooks($store->restaurant_uuid);
    }

    /**
     * @param $id
     * @return array
     */
    public function actionOrder($id) {

        $data = [];

        if (isset($_POST['txn'])) {
            $txn = Yii::$app->request->getBodyParam("txn");
        } else {
            $txn = TabbyTransaction::getTransaction($id);
        }

        if (!empty($txn)) {
            $data['txn']  = json_decode($txn, true);
            $data['captures_total'] = 0;
            $data['refunds_total'] = 0;
            if (!empty($data['txn']['captures'])) {
                foreach ($data['txn']['captures'] as $capture) {
                    $data['captures_total'] += $capture['amount'];
                }
            }
            if (!empty($data['txn']['refunds'])) {
                foreach ($data['txn']['refunds'] as $refund) {
                    $data['refunds_total'] += $refund['amount'];
                }
            }
            $data['max_amount'] = $data['txn']['amount'] - $data['captures_total'];
            $data['max_refund_amount'] = $data['txn']['amount'] - $data['refunds_total'];
        } else {
            $data['text_no_transaction'] = Yii::t("app", "No transaction information available");
        }

        return $data;
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStore($store_uuid =  null)
    {
        $model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }

}