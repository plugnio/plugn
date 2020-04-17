<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
use common\models\Restaurant;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index', 'signup'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'promote-to-open', 'promote-to-close', 'pay', 'callback', 'vendor-dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'login',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays landing page
     *
     * @return mixed
     */
    public function actionIndex() {
        $this->layout = 'landing';

        if (Yii::$app->user->isGuest)
            return $this->render('landing');
        else {
            foreach (Yii::$app->ownedAccountManager->getOwnedRestaurants() as $restaurantOwned) {
                return $this->redirect(['vendor-dashboard', 'id' => $restaurantOwned->restaurant_uuid]);
            }
        }
    }

    /**
     * Displays vendor dashboard homepage.
     *
     * @return mixed
     */
    public function actionVendorDashboard($id) {
        return $this->render('index', [
                    'restaurant_model' => Yii::$app->ownedAccountManager->getOwnedAccount($id)
        ]);
    }

    /**
     * Change restaurant status to become open
     * @param integer $id => restaurant_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToOpen($id) {

        $model = $this->findModel($id);
        $model->promoteToOpenRestaurant();

        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Change restaurant status to become busy
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToBusy($id) {
        $model = $this->findModel($id);
        $model->promoteToBusyRestaurant();

        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Change restaurant status to become close
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToClose($id) {
        $model = $this->findModel($id);
        $model->promoteToCloseRestaurant();

        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
      exec("mpg321 --quiet --gain 10 /path/to/beep.mp3");

    
        $this->layout = 'landing';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $ownedRestaurant = $model->login()) {
            return $this->redirect(['site/vendor-dashboard', 'id' => $ownedRestaurant->restaurant_uuid]);
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionSignup() {
        
        $this->layout = 'landing';

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Yii::$app->ownedAccountManager->getOwnedAccount($id))) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
