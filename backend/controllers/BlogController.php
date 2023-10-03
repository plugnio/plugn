<?php

namespace backend\controllers;

use Yii;
use common\models\Addon;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();

       // Yii::$app->blogManager->token = Yii::$app->user->identity->getAuthKey();
    }

    /**
     * Lists all Addon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $page = Yii::$app->request->get('page', 1);
        $query = Yii::$app->request->get('query');

        $response = Yii::$app->blogManager->listPost($page, $query);

        return $this->render('index', [
            'posts' => $response->data,
            'totalCount' => $response->headers->get('total-count'),
        ]);
    }

    /**
     * Displays a single Addon model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $post = $this->findModel($id);

        return $this->render('view', [
            'post' => $post
        ]);
    }

    /**
     * Creates a new Addon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {

            $params = Yii::$app->request->getBodyParams();

            $params['sort_number'] = (int)$params['sort_number'];
            $params['blogPostCategories'] = [];
            unset($params['_csrf-backend']);

            $response = Yii::$app->blogManager->createPost($params);

            if($response->getStatusCode() != 200) {

                Yii::$app->session->addFlash("error", json_encode($response->data['message']));

                return $this->render('create', [
                    "post" => $params
                ]);
            }

            return $this->redirect(['view', 'id' => $response->data['blogPost']['ID']]);
        }

        return $this->render('create', [
            'post' => null
        ]);
    }

    /**
     * Updates an existing Addon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $params = $this->findModel($id);

        if (Yii::$app->request->isPost) {

            $params = Yii::$app->request->getBodyParams();

            $params['ID'] = (int)$id;
            $params['sort_number'] = (int) $params['sort_number'];
            $params['blogPostDescriptions'][0]["ID"] = (int) $params['blogPostDescriptions'][0]["ID"];
            $params['blogPostDescriptions'][0]["blog_post_id"] = (int)$id;
            $params['blogPostDescriptions'][1]["ID"] = (int) $params['blogPostDescriptions'][1]["ID"];
            $params['blogPostDescriptions'][1]["blog_post_id"] = (int)$id;
            $params['blogPostCategories'] = [];
            unset($params['_csrf-backend']);

            $response = Yii::$app->blogManager->updatePost($id, $params);

            if($response->getStatusCode() != 200) {
                Yii::$app->session->addFlash("error", json_encode($response->data['message']));
            } else {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'post' => $params,
        ]);
    }

    /**
     * Deletes an existing Addon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $response = Yii::$app->blogManager->deletePost($id);

        if($response->getStatusCode() != 200) {
            Yii::$app->session->addFlash("error", json_encode($response->data['message']));

            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Addon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Addon the loaded model
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