<?php

namespace backend\controllers;

use Yii;
use common\models\Restaurant;
use common\models\TapQueue;
use backend\models\RestaurantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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

    /**
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RestaurantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Restaurant model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        $model = $this->findModel($id);

        // Store theme color
        $storeThemeColors = new \yii\data\ActiveDataProvider([
            'query' => $model->getRestaurantTheme(),
            'pagination' => false
        ]);


        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'storeThemeColors' => $storeThemeColors
        ]);
    }

    /**
     * Delete build js
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteSpecificFile($filePath, $id) {

        $store = $this->findModel($id);


        //Replace test with store branch name
        $getBuildJsSHA = Yii::$app->githubComponent->getFileSHA($filePath, $store->store_branch_name);

        if ($getBuildJsSHA->isOk && $getBuildJsSHA->data) {

            $deleteBuildJs = Yii::$app->githubComponent->deleteFile($filePath, $getBuildJsSHA->data['sha'],  $store->store_branch_name);

            if (!$deleteBuildJs->isOk) {
              Yii::error('[Github > Error While deleting'. $filePath . ']' . json_encode($deleteBuildJs->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
              Yii::$app->session->setFlash('errorResponse', json_encode($deleteBuildJs->data['message']));
              return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
            }
      } else {
        Yii::error('[Github > Error while getting file sha]' . json_encode($getBuildJsSHA->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
        Yii::$app->session->setFlash('errorResponse', json_encode($getBuildJsSHA->data['message']));
        return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
      }

      return $this->redirect(['view', 'id' => $store->restaurant_uuid]);

    }

    /**
     * Merge
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMergeToMasterBranch($id) {

        $store = $this->findModel($id);

        //Delete src/environments/environment.develop.ts file
        $getDevEnvFile = Yii::$app->githubComponent->getFileSHA('src/environments/environment.develop.ts', $store->store_branch_name);
        if ($getDevEnvFile->isOk && $getDevEnvFile->data) {
            $deleteDevEnvFile = Yii::$app->githubComponent->deleteFile('src/environments/environment.develop.ts', $getDevEnvFile->data['sha'],  $store->store_branch_name);
            if (!$deleteDevEnvFile->isOk){
              Yii::error('[Github > Error While deleting environment.develop.ts]' . json_encode($deleteDevEnvFile->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
              Yii::$app->session->setFlash('errorResponse', json_encode($deleteDevEnvFile->data['message']));
              return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
            }
        }

        //Delete branch-name.txt file
        $getBranchNameFile = Yii::$app->githubComponent->getFileSHA('branch-name.txt', $store->store_branch_name);
        if ($getBranchNameFile->isOk && $getBranchNameFile->data) {
            $deleteBranchNameFile = Yii::$app->githubComponent->deleteFile('branch-name.txt', $getBranchNameFile->data['sha'],  $store->store_branch_name);
            if (!$deleteBranchNameFile->isOk){
              Yii::error('[Github > Error While deleting branch-name.txt]' . json_encode($deleteDevEnvFile->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
              Yii::$app->session->setFlash('errorResponse', json_encode($deleteDevEnvFile->data['message']));
              return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
            }
        }


        //Delete Build.js file
        $getBuildJsSHA = Yii::$app->githubComponent->getFileSHA('build.js', $store->store_branch_name);
        if ($getBuildJsSHA->isOk && $getBuildJsSHA->data) {
            $deleteBuildJs = Yii::$app->githubComponent->deleteFile('build.js', $getBuildJsSHA->data['sha'],  $store->store_branch_name);
            if (!$deleteBuildJs->isOk){
              Yii::error('[Github > Error While deleting build.js]' . json_encode($deleteDevEnvFile->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
              Yii::$app->session->setFlash('errorResponse', json_encode($deleteDevEnvFile->data['message']));
              return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
            }
        } else {
          Yii::error('[Github > Error while getting file sha]' . json_encode($getBuildJsSHA->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
          Yii::$app->session->setFlash('errorResponse', json_encode($getBuildJsSHA->data['message']));
          return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
        }


        $mergeToMasterResponse = Yii::$app->githubComponent->mergeABranch('Merge branch master-temp into ' . $store->store_branch_name, $store->store_branch_name,  'master-temp');

        if ($mergeToMasterResponse->isOk) {

          $mergeToDevelopResponse = Yii::$app->githubComponent->mergeABranch('Merge branch master into ' . $store->store_branch_name, $store->store_branch_name,  'master');

          if ($mergeToDevelopResponse->isOk) {
              $store->sitemap_require_update = 1;
              $store->version = 2;
              $store->save(false);
          } else {
            Yii::error('[Github > Error While merging with master]' . json_encode($mergeToDevelopResponse->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
            Yii::$app->session->setFlash('errorResponse', json_encode($mergeToMasterResponse->data['message']));
            return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
          }


        } else {
          Yii::error('[Github > Error While merging with Master-staging]' . json_encode($mergeToMasterResponse->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
          Yii::$app->session->setFlash('errorResponse', json_encode($mergeToMasterResponse->data['message']));
          return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
        }


      return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
    }


    /**
     * Merge
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMergeBranch($id, $head) {

        $store = $this->findModel($id);

        $mergeDevelopResponse = Yii::$app->githubComponent->mergeABranch('Merge branch develop into' . $store->store_branch_name, $store->store_branch_name,  $head);

        if (!$mergeDevelopResponse->isOk) {
          Yii::error('[Github > Error While merging with develop]' . json_encode($mergeDevelopResponse->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
          Yii::$app->session->setFlash('errorResponse', json_encode($mergeDevelopResponse->data['message']));
          return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
        }

      return $this->redirect(['view', 'id' => $store->restaurant_uuid]);

    }

    /**
     * Update sitemap
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateSitemap($id) {

        $store = $this->findModel($id);

        $dirName = "../runtime/store";
        if(!file_exists($dirName))
          $createStoreFolder = mkdir($dirName);

        if (!file_exists( $dirName . "/" . $store->store_branch_name )) {
          $myFolder = mkdir( $dirName . "/" . $store->store_branch_name);
        }

      $sitemap =  fopen($dirName . "/" .   $store->store_branch_name . "/sitemap.xml", "w") or die("Unable to open file!");

      fwrite($sitemap, Yii::$app->fileGeneratorComponent->createSitemapXml($store->restaurant_uuid));
      fclose($sitemap);


      $fileToBeUploaded = file_get_contents($dirName .  "/" .   $store->store_branch_name  . "/sitemap.xml");

      // Encode the image string data into base64
      $data = base64_encode($fileToBeUploaded);


      //Replace test with store branch name
      $getSitemapXmlSHA = Yii::$app->githubComponent->getFileSHA('src/sitemap.xml', $store->store_branch_name);

      if ($getSitemapXmlSHA->isOk && $getSitemapXmlSHA->data) {

          $commitSitemapXmlFileResponse = Yii::$app->githubComponent->createFileContent($data, $store->store_branch_name, 'src/sitemap.xml', 'Update sitemap', $getSitemapXmlSHA->data['sha']);

          if ($commitSitemapXmlFileResponse->isOk) {

            if($store->sitemap_require_update == 1){
              $store->sitemap_require_update = 0;
              $store->save(false);
            }

            $dirPath = $dirName . '/'. $store->store_branch_name;
            $file_pointer =  $dirPath . '/sitemap.xml';

            // Use unlink() function to delete a file
            if (!unlink($file_pointer)) {
                Yii::error("$file_pointer cannot be deleted due to an error", __METHOD__);
            } else {
                if (!rmdir($dirPath)) {
                    Yii::error("Could not remove $dirPath", __METHOD__);
                }
            }

          } else {
            Yii::error('[Github > Commit sitemap Xml]' . json_encode($commitSitemapXmlFileResponse->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
            return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
          }
    } else {
      Yii::error('[Github > Error while getting file sha]' . json_encode($getSitemapXmlSHA->data['message']) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);
      return $this->redirect(['view', 'id' => $store->restaurant_uuid]);
    }

      return $this->redirect(['view', 'id' => $store->restaurant_uuid]);

    }


    /**
     * Creates a new Tap account
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateTapAccount($restaurant_uuid) {
        $model = $this->findModel($restaurant_uuid);
        $model->setScenario(Restaurant::SCENARIO_CREATE_TAP_ACCOUNT);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $restaurant_authorized_signature_file = UploadedFile::getInstances($model, 'restaurant_authorized_signature_file');
            $restaurant_commercial_license_file = UploadedFile::getInstances($model, 'restaurant_commercial_license_file');

            $owner_identification_file_front_side = UploadedFile::getInstances($model, 'owner_identification_file_front_side');
            $owner_identification_file_back_side = UploadedFile::getInstances($model, 'owner_identification_file_back_side');

            if (sizeof($restaurant_commercial_license_file) > 0)
                $model->restaurant_commercial_license_file = $restaurant_commercial_license_file[0]; //Commercial License

            if (sizeof($restaurant_authorized_signature_file) > 0)
                $model->restaurant_authorized_signature_file = $restaurant_authorized_signature_file[0]; //Authorized signature

            if (sizeof($owner_identification_file_front_side) > 0)
                $model->owner_identification_file_front_side = $owner_identification_file_front_side[0]; //Owner's civil id front side

            if (sizeof($owner_identification_file_back_side) > 0)
                $model->owner_identification_file_back_side = $owner_identification_file_back_side[0]; //Owner's civil id back side



            $model->createTapAccount();


            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
            } else {
                Yii::$app->session->setFlash('error', print_r($model->errors, true));
            }
        }

        return $this->render('create_tap_account', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new Restaurant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Restaurant();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->restaurant_payments_method)
                $model->saveRestaurantPaymentMethod($model->restaurant_payments_method);


            $thumbnail_image = UploadedFile::getInstances($model, 'restaurant_thumbnail_image');

            $logo = UploadedFile::getInstances($model, 'restaurant_logo');

            if ($thumbnail_image)
                $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

            if ($logo)
                $model->uploadLogo($logo[0]->tempName);


            return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Restaurant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($model->restaurant_payments_method)
                $model->saveRestaurantPaymentMethod($model->restaurant_payments_method);


            if ($model->save()) {

                $thumbnail_image = UploadedFile::getInstances($model, 'restaurant_thumbnail_image');

                $logo = UploadedFile::getInstances($model, 'restaurant_logo');

                if ($thumbnail_image)
                    $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

                if ($logo)
                    $model->uploadLogo($logo[0]->tempName);

                return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
            }else {
                Yii::$app->session->setFlash('error', print_r($model->errors, true));
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }


    /**
     * Display request driver button on order detail page
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDisplayRequestDriverButton($id) {
        $model = $this->findModel($id);
        $model->hide_request_driver_button = 0;
        $model->save(false);

        return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    }

    /**
     * Hide request driver button on order detail page
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHideRequestDriverButton($id) {
        $model = $this->findModel($id);
        $model->hide_request_driver_button = 1;
        $model->save(false);

        return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    }

    // /**
    //  * Change restaurant status to become open
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionPromoteToOpen($id) {
    //     $model = $this->findModel($id);
    //     $model->promoteToOpenRestaurant();
    //
    //     return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    // }
    //
    // /**
    //  * Change restaurant status to become busy
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionPromoteToBusy($id) {
    //     $model = $this->findModel($id);
    //     $model->promoteToBusyRestaurant();
    //
    //     return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    // }
    //
    // /**
    //  * Change restaurant status to become close
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionPromoteToClose($id) {
    //     $model = $this->findModel($id);
    //     $model->promoteToCloseRestaurant();
    //
    //     return $this->redirect(['view', 'id' => $model->restaurant_uuid]);
    // }

    /**
     * Deletes an existing Restaurant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Restaurant::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
