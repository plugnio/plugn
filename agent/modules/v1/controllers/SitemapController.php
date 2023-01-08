<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use api\models\Item;
use yii\web\NotFoundHttpException;


/**
 * Sitemap controller
 */
class SitemapController extends BaseController
{

    /**
     * sitemap xml for search engine
     * @return XML
     */
    public function actionIndex()
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount();

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
}
