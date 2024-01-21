<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use agent\models\OpeningHour;

class OpeningHoursController extends BaseController
{
    /**
     * only owner will have access
     */
    private function ownerCheck()
    {
        if (!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to view discounts. Please contact with store owner')
            );
        }

        //should have access to store
        Yii::$app->accountManager->getManagedAccount();
        return true;
    }

    /**
     * Get all opening hours store
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = OpeningHour::find()
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy(['day_of_week' => SORT_ASC, 'open_at' => SORT_ASC]);

        return new ActiveDataProvider([
            'query' => $query
        ]);

    }

    /**
     * Create opening hours
     * @return array
     */
    public function actionCreate($store_uuid = null)
    {
//        $this->ownerCheck();
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);
        $opening_hours = Yii::$app->request->getBodyParam("opening_hours");

        if (is_array($opening_hours) && sizeof($opening_hours) > 0) {

            foreach ($opening_hours as $key => $opening_hour) {

                $model = new OpeningHour();
                $model->restaurant_uuid = $store->restaurant_uuid;

                $model->day_of_week = $opening_hour['day_of_week'];
                $model->open_at = date('H:i:s', strtotime($opening_hour['open_at']));
                $model->close_at = date('H:i:s', strtotime($opening_hour['close_at']));

                /*if (strtotime ($opening_hour['close_at']) <= strtotime ($opening_hour['open_at']) ) {
                    return [
                        "operation" => "error",
                        "message" => Yii::t ('agent', "Close time should be greater than Open time")
                    ];
                }*/

                if (!$model->save()) {
                    if (isset($model->errors)) {
                        return [
                            "operation" => "error",
                            "message" => $model->errors
                        ];
                    } else {
                        return [
                            "operation" => "error",
                            "message" => Yii::t('agent', "We've faced a problem adding the Opening Hour")
                        ];
                    }
                }
            }
            return [
                "operation" => "success",
                "message" => Yii::t('agent', "Opening Hour created successfully")
            ];
        }
    }

    /**
     * Update opening hours
     * @return array
     */
    public function actionUpdate($day_of_week)
    {
//        $this->ownerCheck();
        $opening_hours = Yii::$app->request->getBodyParam("opening_hours");

        //validate

        $store = Yii::$app->accountManager->getManagedAccount();

        //remove old

        OpeningHour::deleteAll([
            'day_of_week' => $day_of_week,
            'restaurant_uuid' => $store->restaurant_uuid
        ]);

        $props = [];

        //add new timeslots
        foreach ($opening_hours as $key => $opening_hour) {

            $model = new OpeningHour;
            $model->restaurant_uuid = $store->restaurant_uuid;
            $model->day_of_week = $day_of_week;
            $model->open_at = date('H:i:s', strtotime($opening_hour['open_at']));
            $model->close_at = date('H:i:s', strtotime($opening_hour['close_at']));

            /*if (strtotime ($opening_hour['close_at']) <= strtotime ($opening_hour['open_at']) ) {
                return [
                    "operation" => "error",
                    "message" => Yii::t ('agent', "Close time should be greater than Open time")
                ];
            }*/

            if (!$model->save()) {
                if (isset($model->errors)) {
                    return [
                        "operation" => "error",
                        "message" => $model->errors
                    ];
                } else {
                    return [
                        "operation" => "error",
                        "message" => Yii::t('agent', "We've faced a problem updating the Opening Hour")
                    ];
                }
            }

            $props[] = [$model->getDayOfWeek() => [
                "open_time" => $model->open_at,
                "close_time" => $model->close_at
            ]];
        }

        if (YII_ENV == 'prod') {
            Yii::$app->eventManager->track("Opening Hours Updated", $props);
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Opening Hour updated successfully")
        ];
    }

    /**
     * Return OpeningHour detail
     * @param type $store_uuid
     * @param type $opening_hour_id
     * @return type
     */
    public function actionDetail($store_uuid = null, $day_of_week)
    {
//        $this->ownerCheck();
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if (($model = OpeningHour::find()->where(['day_of_week' => $day_of_week, 'restaurant_uuid' => $store->restaurant_uuid])) !== null) {

            return new ActiveDataProvider([
                'query' => $model
            ]);

        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }

    }


    /**
     * Delete Opening hours
     */
    public function actionDelete($opening_hour_id, $store_uuid = null)
    {
        $this->ownerCheck();
        $model = $this->findModel($opening_hour_id, $store_uuid);
        if (!$model->delete()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting opening hours")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Opening Hour deleted successfully")
        ];
    }

    /**
     * Finds the OpeningHour model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpeningHour the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($opening_hour_id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = OpeningHour::find()
            ->where([
                'opening_hour_id' => $opening_hour_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
