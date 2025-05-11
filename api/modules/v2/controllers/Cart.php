<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class Cart extends BaseController
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        return $behaviors;
    }

    /**
     * Lists all Cart models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->findModel();
    }

    /**
     * @return string[]
     * @throws ServerErrorHttpException
     */
    public function actionAdd() {
        $cart = $this->findModel();

        $item = new \common\models\CartItem();
        $item->cart_uuid = $cart->cart_uuid;
        $item->item_uuid = Yii::$app->request->post('item_uuid');
        $item->item_variant_uuid = Yii::$app->request->post('item_variant_uuid');
        $item->qty = Yii::$app->request->post('qty', 1);
        $item->itemOptions = Yii::$app->request->post('itemOptions', []);
        //$item->key = Yii::$app->request->post('key', $item->item_uuid . '-' . $item->item_variant_uuid);

        if ($item->save()) {
            return ['status' => 'success', 'message' => 'Item added to cart'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to add item to cart'];
        }
    }

    /**
     * @param $id
     * @return string[]
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id) {

        $qty = Yii::$app->request->getBodyParam('qty', 1);

        $cart = $this->findModel();

        $item = $cart->getCartItems()
            ->andWhere(['cart_item_uuid' => $id])
            ->one();

        if (!$item) {
            throw new NotFoundHttpException("Item not found");
        }

        if ($qty > 0) {
            $item->qty = $qty;

            if (!$item->save()) {
                return ['operation' => 'error', 'message' => 'Failed to update item'];
            }
        } else {
            if (!$item->delete()) {
                return ['operation' => 'error', 'message' => 'Failed to delete item'];
            }
        }

        return ['operation' => 'success', 'message' => 'Item updated'];
    }

    /**
     * Finds the Cart model based on the primary key.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return \common\models\Cart|null
     * @throws ServerErrorHttpException if there is any error when saving the model
     */
    public function findModel()
    {
        $cart = Yii::$app->user->isGuest? \common\models\Cart::find()
                ->where(['session_id' => Yii::$app->session->id])
                ->one():
            \common\models\Cart::find()
                ->where(['customer_id' => Yii::$app->user->id])
                ->one();

        if (!$cart) {
            $cart = new \common\models\Cart();
            $cart->session_id = Yii::$app->session->id;
            $cart->customer_id = Yii::$app->user->id;
            if (!$cart->save()) {
                throw new ServerErrorHttpException(json_encode($cart->getErrors()));
            }
        }

        return $cart;
    }
}