<?php

namespace api\modules\v2\controllers;

use Yii;
use common\models\Blog;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class BlogController extends BaseController
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
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

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
     * Lists all Blog models.
     * @return mixed
     */
    public function actionList()
    {
        $page = Yii::$app->request->get('page', 1);
        $query = Yii::$app->request->get('query');

        $response = Yii::$app->blogManager->listPost($page, $query);

        /*$response =
        $headers = Yii::$app->response->headers;
        $headers->set('X-Pagination-Current-Page', 'no-cache');
        $headers->set('X-Pagination-Page-Count', 'no-cache');
        $headers->set('X-Pagination-Per-Page', 'no-cache');
        $headers->set('X-Pagination-Total-Count', 'no-cache');*/

        return $response->data;
    }

    /**
     * Displays a single Blog model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $response = Yii::$app->blogManager->viewPost($id);

        if($response->getStatusCode() != 200 || empty($response->data['ID'])) {
            // Yii::$app->session->addFlash("error", json_encode($response->data['message']));

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        return $response->data;
    }
}