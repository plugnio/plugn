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

            $mixpanel = Yii::$app->request->post('Mixpanel-Key');
            $mixpanelStatus = Yii::$app->request->post('Mixpanel-Status');

            $segment = Yii::$app->request->post('Segment-Key');
            $segmentStatus = Yii::$app->request->post('Segment-Status');
            $segmentWallet = Yii::$app->request->post('Segment-Key-Wallet');

            Setting::setConfig(null, 'EventManager', 'Mixpanel-Key', $mixpanel);
            Setting::setConfig(null,'EventManager', 'Mixpanel-Status', $mixpanelStatus ? "enabled" : null);

            Setting::setConfig(null,'EventManager', 'Segment-Key', $segment);
            Setting::setConfig(null,'EventManager', 'Segment-Status', $segmentStatus ? "enabled" : null);
            Setting::setConfig(null,'EventManager', 'Segment-Key-Wallet', $segmentWallet);

            Yii::$app->session->addFlash("success", "Settings updated successfully!");
        }

        $settings = Setting::find()->all();

        return $this->render('form', [
            'settings' => ArrayHelper::map($settings, 'key', 'value')
        ]);
    }
}
