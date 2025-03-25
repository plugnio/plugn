<?php

namespace backend\controllers;

use common\models\Setting;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Setting controller - Manage settings as Admin
 */
class SettingController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Update settings
     * @return mixed
     */
    public function actionUpdate()
    {
        if(Yii::$app->request->isPost) {

            //mixpanel

            $mixpanelStatus = Yii::$app->request->post('Mixpanel-Status');

            $mixpanel = Yii::$app->request->post('Mixpanel-Key');
            $testMixpanel = Yii::$app->request->post('Test-Mixpanel-Key');

            $mixpanelWallet = Yii::$app->request->post('Mixpanel-Key-Wallet');
            $testMixpanelWallet = Yii::$app->request->post('Test-Mixpanel-Key-Wallet');

            //segment

            $segmentStatus = Yii::$app->request->post('Segment-Status');

            $segment = Yii::$app->request->post('Segment-Key');
            $segmentWallet = Yii::$app->request->post('Segment-Key-Wallet');

            $testSegment = Yii::$app->request->post('Test-Segment-Key');
            $testSegmentWallet = Yii::$app->request->post('Test-Segment-Key-Wallet');

            $result = Setting::setConfig(null,'EventManager', 'Mixpanel-Status', $mixpanelStatus ? "enabled" : null);

            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null, 'EventManager', 'Mixpanel-Key', $mixpanel);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Test-Mixpanel-Key', $testMixpanel);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null, 'EventManager', 'Mixpanel-Key-Wallet', $mixpanelWallet);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Test-Mixpanel-Key-Wallet', $testMixpanelWallet);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Segment-Status', $segmentStatus ? "enabled" : null);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Segment-Key', $segment);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Segment-Key-Wallet', $segmentWallet);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Test-Segment-Key', $testSegment);
            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            $result = Setting::setConfig(null,'EventManager', 'Test-Segment-Key-Wallet', $testSegmentWallet);

            if ($result['operation'] != "success") {
                Yii::$app->session->addFlash("error", $result['message']);
                return $this->redirect(['setting/update']);
            }

            Yii::$app->session->addFlash("success", "Settings updated successfully!");
        }

        $settings = Setting::find()->all();

        return $this->render('form', [
            'settings' => ArrayHelper::map($settings, 'key', 'value')
        ]);
    }
}
