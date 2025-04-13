<?php

namespace api\modules\v2\controllers;

use Yii;
use common\models\CustomerAddress;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;


class AddressController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

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
    public function actions()
    {
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
     * @return array
     * 
     * @api {get} /addresses List addresses
     * @apiName ListAddresses
     * @apiGroup Address
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionList()
    {
        $query = Yii::$app->user->identity
            ->getCustomerAddresses();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return array
     * 
     * @api {post} /addresses Add address
     * @apiName AddAddress
     * @apiGroup Address
     * 
     * @apiParam {string} address_id Address ID.
     * @apiParam {string} area_id Area ID.
     * @apiParam {string} city_id City ID.
     * @apiParam {string} country_id Country ID.
     * @apiParam {string} unit_type Unit type.
     * @apiParam {string} house_number House number.
     * @apiParam {string} floor Floor.
     * @apiParam {string} apartment Apartment.
     * @apiParam {string} building Building.
     * @apiParam {string} block Block.
     * @apiParam {string} street Street.
     * @apiParam {string} avenue Avenue.
     * @apiParam {string} office Office.
     * @apiParam {string} postalcode Postal code.
     * @apiParam {string} address_1 Address 1.
     * @apiParam {string} address_2 Address 2.
     * @apiParam {string} special_directions Special directions.
     * @apiParam {string} delivery_instructions Delivery instructions.
     *  
     * @apiSuccess {string} message Message.
     */
    public function actionAdd()
    {
        $address = new CustomerAddress();
        $address->address_id = Yii::$app->request->getBodyParam('address_id');
        $address->customer_id = Yii::$app->user->getId();
        $address->area_id = Yii::$app->request->getBodyParam('area_id');
        $address->city_id  = Yii::$app->request->getBodyParam('city_id');
        $address->country_id  = Yii::$app->request->getBodyParam('country_id');
        $address->unit_type  = Yii::$app->request->getBodyParam('unit_type');
        $address->house_number  = Yii::$app->request->getBodyParam('house_number');
        $address->floor  = Yii::$app->request->getBodyParam('floor');
        $address->apartment  = Yii::$app->request->getBodyParam('apartment');
        $address->building  = Yii::$app->request->getBodyParam('building');
        $address->block  = Yii::$app->request->getBodyParam('block');
        $address->street  = Yii::$app->request->getBodyParam('street');
        $address->avenue  = Yii::$app->request->getBodyParam('avenue');
        $address->office  = Yii::$app->request->getBodyParam('office');
        $address->postalcode  = Yii::$app->request->getBodyParam('postalcode');
        $address->address_1  = Yii::$app->request->getBodyParam('address_1');
        $address->address_2 = Yii::$app->request->getBodyParam('address_2');
        $address->special_directions = Yii::$app->request->getBodyParam('special_directions');
        $address->delivery_instructions = Yii::$app->request->getBodyParam('delivery_instructions');

        if (!$address->city_id && $address->area) {
            $address->city_id = $address->area->city_id;
        }

        if(!$address->save()) {
            return [
                "operation" => "error",
                "message" => $address->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('customer', "Address added successfully!")
        ];
    }

    /**
     * @return array
     * 
     * @api {patch} /addresses/:id Update address
     * @apiName UpdateAddress
     * @apiGroup Address
     * 
     * @apiParam {string} area_id Area ID.
     * @apiParam {string} city_id City ID.
     * @apiParam {string} country_id Country ID.
     * @apiParam {string} unit_type Unit type.
     * @apiParam {string} house_number House number.
     * @apiParam {string} floor Floor.
     * @apiParam {string} apartment Apartment.
     * @apiParam {string} building Building.
     * @apiParam {string} block Block.
     * @apiParam {string} street Street.
     * @apiParam {string} avenue Avenue.
     * @apiParam {string} office Office.
     * @apiParam {string} postalcode Postal code.
     * @apiParam {string} address_1 Address 1.
     * @apiParam {string} address_2 Address 2.
     * @apiParam {string} special_directions Special directions.
     * @apiParam {string} delivery_instructions Delivery instructions.
     *  
     * @apiSuccess {string} message Message.    
     */
    public function actionUpdate($id)
    {
        $address = $this->findModel($id);
        $address->area_id = Yii::$app->request->getBodyParam('area_id');
        $address->city_id  = Yii::$app->request->getBodyParam('city_id');
        $address->country_id  = Yii::$app->request->getBodyParam('country_id');
        $address->unit_type  = Yii::$app->request->getBodyParam('unit_type');
        $address->house_number  = Yii::$app->request->getBodyParam('house_number');
        $address->floor  = Yii::$app->request->getBodyParam('floor');
        $address->apartment  = Yii::$app->request->getBodyParam('apartment');
        $address->building  = Yii::$app->request->getBodyParam('building');
        $address->block  = Yii::$app->request->getBodyParam('block');
        $address->street  = Yii::$app->request->getBodyParam('street');
        $address->avenue  = Yii::$app->request->getBodyParam('avenue');
        $address->office  = Yii::$app->request->getBodyParam('office');
        $address->postalcode  = Yii::$app->request->getBodyParam('postalcode');
        $address->address_1  = Yii::$app->request->getBodyParam('address_1');
        $address->address_2 = Yii::$app->request->getBodyParam('address_2');
        $address->special_directions = Yii::$app->request->getBodyParam('special_directions');
        $address->delivery_instructions = Yii::$app->request->getBodyParam('delivery_instructions');

        if (!$address->city_id && $address->area) {
            $address->city_id = $address->area->city_id;
        }

        if(!$address->save()) {
            return [
                "operation" => "error",
                "message" => $address->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('customer', "Address updated successfully!")
        ];
    }

    /**
     * @return array
     * 
     * @api {get} /addresses/:id Detail address
     * @apiName DetailAddress
     * @apiGroup Address
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionDetail($id)
    {
        return $this->findModel($id);
    }


    /**
     * @return array
     * 
     * @api {delete} /addresses/:id Delete address
     * @apiName DeleteAddress
     * @apiGroup Address
     * 
     * @apiSuccess {string} message Message.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!$model->delete()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('customer', "Address deleted successfully!")
        ];
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerAddress the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Yii::$app->user->identity
            ->getCustomerAddresses()
            ->andWhere(['address_id' => $id])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}