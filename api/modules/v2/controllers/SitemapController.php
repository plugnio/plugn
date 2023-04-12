<?php

namespace api\modules\v2\controllers;

use Yii;
use api\models\Category;
use api\models\Restaurant;
use yii\web\Controller;
use api\models\Item;
use yii\web\NotFoundHttpException;


/**
 * Sitemap controller
 */
class SitemapController extends Controller
{
    public function actionIndex($storeUuid)
    {
        $restaurant = $this->findModel($storeUuid);

        $products = $restaurant->getItems()
            ->orderBy('item_created_at DESC')
            //->limit(50)
            ->all();

        $categories = $restaurant->getCategories()
            ->orderBy('sort_number')
            //->limit(50)
            ->all();

        header("Content-type: text/xml");

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        return $this->renderPartial('index', [
            'products' => $products,
            'categories' => $categories,
            'restaurant' => $restaurant
        ]);
    }


    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Restaurant::find()
            ->where(['restaurant_uuid' => $id])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
