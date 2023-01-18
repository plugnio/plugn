<?php

namespace agent\modules\v1\controllers;

use common\models\RestaurantInvoice;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


class InvoiceController extends BaseController
{
    /**
     * Get all store's products
     * @return type
     */
    public function actionList()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $query = $store->getInvoices()->orderBy('created_at desc');

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return invoice detail
     * @param string $id
     * @return RestaurantInvoice
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }

    //todo: ability to pay for invoice

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $invoice_uuid
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($invoice_uuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = $store->getInvoices()->andWhere(['invoice_uuid' => $invoice_uuid])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}