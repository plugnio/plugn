<?php

namespace agent\modules\v1\controllers;

use agent\models\Country;
use api\models\City;
use api\models\State;
use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Area;

/**
 * AreaController implements the CRUD actions for Area model.
 */
class AreaController extends BaseController {

    /**
     * @return ActiveDataProvider
     *
     * @api {get} /areas List areas
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiParam {string} [city_id] City id.
     * @apiParam {string} [store_id] Store id.
     * @apiName ListAreas
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of areas.
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $city_id = Yii::$app->request->get('city_id');
        $store_id = Yii::$app->request->get('store_id');

        //valdiate access
        
        if ($store_id) {
            Yii::$app->accountManager->getManagedAccount($store_id);
        }

        $query =  Area::find();

        if ($city_id) {
            $query->andWhere(['city_id' => $city_id]);
        }

        if ($store_id) {
            $query->joinWith('restaurant');
            $query->andWhere(['restaurant.restaurant_uuid' => $store_id]);
        }

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return ActiveDataProvider
     * 
     * @api {get} /areas/delivery-areas List delivery areas
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiParam {string} [city_id] City id.
     * @apiParam {string} [store_id] Store id.
     * @apiName ListDeliveryAreas
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of area delivery zones.
     */
    public function actionDeliveryAreas() {

        $keyword = Yii::$app->request->get('keyword');
        $city_id = Yii::$app->request->get('city_id');
        $store_id = Yii::$app->request->get('store_id');

        //valdiate access

        $store = Yii::$app->accountManager->getManagedAccount($store_id);

        $query = $store->getAreaDeliveryZones()
            ->andWhere(new Expression('area_delivery_zone.area_id IS NOT NULL'));

        if ($city_id) {
            $query->andWhere(['city_id' => $city_id]);
        }

        if ($store_id) {
            //$query->joinWith('restaurant');
            $query->andWhere(['restaurant_uuid' => $store_id]);
        }

        if ($keyword) {
            $query
                ->joinWith('area')
            ->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return Area detail
     * @param integer $area_id
     * @return Area
     * 
     * @api {get} /areas/:id Get area detail
     * @apiParam {string} [area_id] Area id.
     * @apiName GetAreaDetail
     * @apiGroup Area
     *
     * @apiSuccess {id} area_id Area id.
     * @apiSuccess {id} city_id City id.
     * @apiSuccess {string} area_name Area name.
     * @apiSuccess {string} area_name_ar Area name in Arabic.
     * @apiSuccess {string} latitude Latitude.
     * @apiSuccess {string} longitude Longitude.
     */
    public function actionDetail($id) {
        return $this->findModel($id);
    }

    /**
     * @return void
     * 
     * @api {get} /areas/city-by-location Get city by location
     * @apiName GetCityByLocation
     * @apiParam {string} latitude Latitude.
     * @apiParam {string} longitude Longitude.
     * @apiParam {string} postal_code Postal code.
     * @apiGroup Area
     *
     * @apiSuccess {id} city_id City id.
     * @apiSuccess {string} state_id State id.
     * @apiSuccess {string} country_id Country id.
     * @apiSuccess {string} city_name City name.
     * @apiSuccess {string} city_name_ar City name in Arabic.
     */
    public function actionCityByLocation() {
        $latitude = Yii::$app->request->get('latitude');
        $longitude = Yii::$app->request->get('longitude');
        $postal_code = Yii::$app->request->get("postal_code");

        // call google api to get country name, lat, long

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude .','. $longitude;

        return City::addByGoogleAPIResponse($url, null, null, $postal_code);
    }

    /**
     * @param $city_id
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * 
     * @api {get} /areas/city-areas/:city_id Get city areas
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiName GetCityAreas
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of city areas.
     */
    public function actionCityAreas($city_id)
    {
        $keyword = Yii::$app->request->get("keyword");

        $city = City::findOne($city_id);

        if (!$city) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $city->getAreas("\common\models\Area");

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @param $country_id
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * 
     * @api {get} /areas/country-areas/:country_id Get country areas
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiName GetCountryAreas
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of countries with areas.
     */
    public function actionCountryAreas($country_id)
    {
        $keyword = Yii::$app->request->get("keyword");

        $country = Country::findOne($country_id);

        if (!$country) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $country->getAreas("\common\models\Area");

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @param $country_id
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * 
     * @api {get} /areas/country-states/:country_id Get country states
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiName GetCountryStates
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of countries with states.
     */
    public function actionCountryStates($country_id)
    {
        $keyword = Yii::$app->request->get("keyword");

        $country = Country::findOne($country_id);

        if (!$country) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $country->getStates("\api\models\State");

        if ($keyword) {
            $query->andWhere(['like', 'name', $keyword]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }

    /**
     * @param $country_id
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * 
     * @api {get} /areas/country-cities/:country_id Get country cities
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiName GetCountryCities
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of countries with cities.
     */
    public function actionCountryCities($country_id)
    {
        $keyword = Yii::$app->request->get("keyword");

        $country = Country::findOne($country_id);

        if (!$country) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $country->getCities("\api\models\City");

        if($country && $country->iso == "KW") {
            $query->andWhere(new Expression('state_id IS NULL'));
            //hide areas added as city in kuwait by google api
        }

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'city_name', $keyword],
                ['like', 'city_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @param $state_id
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * 
     * @api {get} /areas/state-cities/:state_id Get state cities
     * @apiParam {string} [keyword] Keyword to search for.
     * @apiName GetStateCities
     * @apiGroup Area
     *
     * @apiSuccess {Array} List of cities available for state.
     */
    public function actionStateCities($state_id)
    {
        $keyword = Yii::$app->request->get("keyword");

        $state = State::findOne($state_id);

        if (!$state) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $state->getCities("\api\models\City");

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'city_name', $keyword],
                ['like', 'city_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Finds the Area  model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($area_id)
    {
        if (($model = Area::findOne ($area_id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
