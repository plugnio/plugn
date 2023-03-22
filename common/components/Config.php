<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Setting;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


class Config extends Component
{
    public $data = [];

    /**
     * Sets up the Config component for use
     *
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        if(isset(Yii::$app->request->headers)) {
            
            $restaurant_uuid = Yii::$app->request->headers->get('Store-Id');

            if($restaurant_uuid) {
                $this->load($restaurant_uuid);
            }
        }

        //load global config 

        $this->loadGlobalConfig();

        parent::__construct($config);
    }

    /**
     *
     *
     * @param	string	$key
     *
     * @return	mixed
     */
    public function get(string $key)  {
        return isset($this->data[$key]) ? $this->data[$key] : '';
    }

    /**
     *
     *
     * @param	string	$key
     * @param	string	$value
     */
    public function set(string $key, mixed $value) {
        $this->data[$key] = $value;
    }

    /**
     *
     *
     * @param	string	$key
     *
     * @return	mixed
     */
    public function has(string $key) {
        return isset($this->data[$key]);
    }

    /**
     * @param $restaurant_uuid
     */
    public function load($restaurant_uuid = null)
    {
        // Getting a list of Restaurant config
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT restaurant_uuid, COUNT(*) FROM setting WHERE restaurant_uuid="'.$restaurant_uuid.'"'
        ]);

        //todo: increase on production?
        $cacheDuration = 60*1; //1 minute then delete from cache

        $this->data = Setting::getDb()->cache(function($db) use($restaurant_uuid) {

            $query = Setting::find();

            if($restaurant_uuid) {
                $query->andWhere(['restaurant_uuid' => $restaurant_uuid]);
            } else {
                $query->andWhere(new Expression('restaurant_uuid IS NULL'));
            }

            $data = $query
                ->all();

            return ArrayHelper::map($data, 'key', 'value');

        }, $cacheDuration, $cacheDependency);
    }

    public function loadGlobalConfig()
    {
        // Getting a list of Restaurant config
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM setting WHERE restaurant_uuid IS NULL'
        ]);

        //todo: increase on production?
        $cacheDuration = 60*1; //1 minute then delete from cache

        $this->data = Setting::getDb()->cache(function($db) {

            $data = Setting::find()
                ->andWhere(new Expression('restaurant_uuid IS NULL'))
                ->all();

            return ArrayHelper::map($data, 'key', 'value');

        }, $cacheDuration, $cacheDependency);
    }
}