<?php

namespace agent\modules\v1\controllers\payment;

use agent\models\Restaurant;
use agent\modules\v1\controllers\BaseController;
use common\models\Tabby;
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

    /**
     * @return void
     * @throws NotFoundHttpException
     * 
     * @api {POST} /payment/tabby/install Install Tabby
     * @apiName InstallTabby
     * @apiGroup Payment
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionInstall() {
        $store = $this->findStore();

        TabbyTransaction::registerWebhooks($store->restaurant_uuid);
    }

    /**
     * @param $id
     * @return array
     * 
     * @api {POST} /payment/tabby/order/:id Order
     * @apiName Order
     * @apiGroup Payment
     * 
     * @apiParam {string} txn Transaction.
     * @apiParam {string} id Transaction ID.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
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
     * @return array
     * 
     * @api {PATCH} /payment/tabby/refund Refund
     * @apiName Refund
     * @apiGroup Payment
     * 
     * @apiParam {string} transaction_id Transaction ID.
     * @apiParam {string} amount Amount.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionRefund() {
        $json = array();

        $transaction_id = Yii::$app->request->get("transaction_id");
        $amount = Yii::$app->request->getBodyParam('amount');

        $tabby = new Tabby();
        $result = $tabby->refund($transaction_id, $amount);

        if ($result['error']) {
            $json['success'] = false;
            $json['error'] = $result['message'];
        } else {
            $json['success'] = "Transaction updated successfully!";
            $json['error'] = false;
        }

        return $json;
    }

    /**
     * @return array
     * 
     * @api {PATCH} /payment/tabby/close Close
     * @apiName Close
     * @apiGroup Payment
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionClose() {
        $json = array();

        $transaction_id = Yii::$app->request->get("transaction_id");

        $tabby = new Tabby();
        $result = $tabby->close($transaction_id);

        if ($result['error']) {
            $json['success'] = false;
            $json['error'] = $result['message'];
        } else {
            $json['success'] = "Transaction updated successfully!";
            $json['error'] = false;
        }

        return $json;
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