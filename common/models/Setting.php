<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "setting".
 *
 * @property string $setting_uuid
 * @property string|null $restaurant_uuid
 * @property string $code module identifier
 * @property string $key
 * @property string|null $value
 * @property int|null $serialized
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Restaurant $restaurantUu
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'key'], 'required'],
            [['value'], 'string'],
            [['serialized'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['setting_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['code', 'key'], 'string', 'max' => 128],
            [['setting_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'setting_uuid',
                ],
                'value' => function () {
                    if (!$this->setting_uuid)
                        $this->setting_uuid = 'setting_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->setting_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'setting_uuid' => Yii::t('app', 'Setting ID'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'code' => Yii::t('app', 'Code'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'serialized' => Yii::t('app', 'Serialized'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * set config
     * @param $restaurant_uuid
     * @param $code
     * @param $key
     * @param $value
     */
    public static function getConfig($restaurant_uuid, $code, $key) {

        //if loaded

        if(Yii::$app->config->has('key'))
        {
            return Yii::$app->config->get($key);
        }

        //if store specific

        $model = Setting::find()
            ->andWhere([
                'restaurant_uuid' => $restaurant_uuid,
                'code' => $code,
                'key' => $key
            ])
            ->one();

        if($model) {
            return $model->value;
        }

        //global

        if(isset(Yii::$app->params[$key])) {
            return Yii::$app->params[$key];
        }
    }

    /**
     * set config
     * @param $restaurant_uuid
     * @param $code
     * @param $key
     * @param $value
     */
    public static function setConfig($restaurant_uuid, $code, $key, $value) {

        //if exists update

        /*if(Yii::$app->config->has($key))
        {
            return self::updateAll([
                'value' => $value
            ], [
                'key' => $key,
            ]);
        }*/

        $model = Setting::find()->andWhere([
            "restaurant_uuid" => $restaurant_uuid,
            "code" => $code,
            "key" => $key
        ])->one();

        if(!$model) {
            $model = new Setting();
            $model->code = $code;
            $model->key = $key;
            $model->restaurant_uuid = $restaurant_uuid;
        }

        $model->value = $value;
        $model->save();
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
