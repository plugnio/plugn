<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use api\models\Item;
use yii\web\NotFoundHttpException;


/**
 * Sitemap controller
 */
class SitemapController extends Controller
{
    public function behaviors() {
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
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
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
