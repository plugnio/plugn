<?php
namespace api\modules\v2\controllers;
use yii\web\Controller;
use common\models\Item;
/**
 * Sitemap controller
 */
class SitemapController extends Controller
{
    public function actionIndex($storeUuid) {
        $products = Item::find()
            ->where(['restaurant_uuid' => $storeUuid])
            ->orderBy('item_created_at DESC')
            ->limit(50)
            ->all();

      header("Content-type: text/xml");
      \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

    	return $this->renderPartial('index', [
            'products' => $products
        ]);
    }
}
