<?php

namespace agent\modules\v1\controllers;

use Yii;
use common\models\Supplier;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class SupplierController extends BaseController
{
    /**
     * @return ActiveDataProvider
     * 
     * @api {get} /suppliers Get suppliers
     * @apiName GetSuppliers
     * @apiGroup Supplier
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionList()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $keyword = Yii::$app->request->get("query");

        $query = Supplier::find()
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy('created_at DESC');

        if ($keyword && $keyword != 'null') {
            $query->andWhere([
                'or',
                ['like', 'name', $keyword],
                ['like', 'contact_info', $keyword],
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     * 
     * @api {get} /suppliers/:id Get supplier
     * @apiName GetSupplier
     * @apiGroup Supplier
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }

    /**
     * @return array|string[]
     * 
     * @api {post} /suppliers Create supplier
     * @apiName CreateSupplier
     * @apiGroup Supplier
     * 
     * @apiParam {string} name Supplier name.
     * @apiParam {string} contact_info Supplier contact info.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionCreate()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $model = new Supplier();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->name = Yii::$app->request->getBodyParam("name");
        $model->contact_info = Yii::$app->request->getBodyParam("contact_info");

        if (!$model->save()) {
            return [
                "message" => $model->errors,
                "operation" => "error"
            ];
        }

        return [
            "message" => 'Added successfully!',
            "operation" => "success"
        ];
    }

    /**
     * @param $id
     * @return array|string[]
     * @throws NotFoundHttpException
     * 
     * @api {PATCH} /suppliers/:id Update supplier
     * @apiName UpdateSupplier
     * @apiGroup Supplier
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->name = Yii::$app->request->getBodyParam("name");
        $model->contact_info = Yii::$app->request->getBodyParam("contact_info");

        if (!$model->save()) {
            return [
                "message" => $model->errors,
                "operation" => "error"
            ];
        }

        return [
            "message" => 'Updated successfully!',
            "operation" => "success"
        ];
    }

    /**
     * @param $id
     * @return array|string[]
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * 
     * @api {DELETE} /suppliers/:id Delete supplier
     * @apiName DeleteSupplier
     * @apiGroup Supplier
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            return [
                "message" => $model->errors,
                "operation" => "error"
            ];
        }

        return [
            "message" => 'Deleted successfully!',
            "operation" => "success"
        ];
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        if (($model = Supplier::find()->where(['supplier_uuid' => $id, 'restaurant_uuid' => $store->restaurant_uuid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}