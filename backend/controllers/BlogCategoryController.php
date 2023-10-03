<?php

namespace backend\controllers;

use yii;
use common\models\Addon;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BlogCategoryController  extends Controller
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

        $response = Yii::$app->blogManager->listCategory($page, $query);

        return $this->render('index', [
            'categories' => $response->data,
            'totalCount' => $response->headers->get('total-count'),
        ]);
    }

    /**
     * Displays a single blog category
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $post = $this->findModel($id);

        return $this->render('view', [
            'category' => $post
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
            unset($params['_csrf-backend']);

            $response = Yii::$app->blogManager->createCategory($params);

            if($response->getStatusCode() != 200) {

                Yii::$app->session->addFlash("error", json_encode($response->data['message']));

                return $this->render('create', [
                    "category" => $params
                ]);
            }

            return $this->redirect(['view', 'id' => $response->data['blogCategory']['ID']]);
        }

        return $this->render('create', [
            'category' => null
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
            $params['blogCategoryDescriptions'][0]["ID"] = (int) $params['blogCategoryDescriptions'][0]["ID"];
            $params['blogCategoryDescriptions'][0]["blog_category_id"] = (int)$id;
            $params['blogCategoryDescriptions'][1]["ID"] = (int) $params['blogCategoryDescriptions'][1]["ID"];
            $params['blogCategoryDescriptions'][1]["blog_category_id"] = (int)$id;

            unset($params['_csrf-backend']);

            $response = Yii::$app->blogManager->updateCategory($id, $params);

            if($response->getStatusCode() != 200) {
                Yii::$app->session->addFlash("error", json_encode($response->data['message']));
            } else {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'category' => $params,
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
        $response = Yii::$app->blogManager->deleteCategory($id);

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
        $response = Yii::$app->blogManager->viewCategory($id);

        if($response->getStatusCode() != 200 || empty($response->data['ID'])) {
            // Yii::$app->session->addFlash("error", json_encode($response->data['message']));

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        return $response->data;
    }
}