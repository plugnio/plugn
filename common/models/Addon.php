<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "addon".
 *
 * @property string $addon_uuid
 * @property string $name
 * @property string $name_ar
 * @property string $description
 * @property string|null $description_ar
 * @property float $price
 * @property float|null $special_price
 * @property string $slug
 * @property int|null $expected_delivery in days
 * @property int|null $sort_number
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Admin $createdBy
 * @property Admin $updatedBy
 * @property AddonPayment[] $addonPayments
 * @property RestaurantAddon[] $restaurantAddons
 */
class Addon extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'addon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_ar', 'description', 'price'], 'required'],
            [['description', 'description_ar', 'slug'], 'string'],
            [['price', 'special_price'], 'number'],
            [['expected_delivery', 'sort_number', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['addon_uuid'], 'string', 'max' => 60],
            [['name', 'name_ar', 'slug'], 'string', 'max' => 100],
            [['addon_uuid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::className(), 'targetAttribute' => ['created_by' => 'admin_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::className(), 'targetAttribute' => ['updated_by' => 'admin_id']],
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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'addon_uuid',
                ],
                'value' => function () {
                    if (!$this->addon_uuid) {
                        $this->addon_uuid = 'addon_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->addon_uuid;
                }
            ],
            [
                'class' => BlameableBehavior::className()
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'ensureUnique' => true,
                'uniqueValidator' => ['targetAttribute' => ['addon_uuid', 'name']]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'addon_uuid' => Yii::t('app', 'Addon Uuid'),
            'name' => Yii::t('app', 'Name'),
            'name_ar' => Yii::t('app', 'Name - Arabic'),
            'description' => Yii::t('app', 'Description'),
            'description_ar' => Yii::t('app', 'Description - Arabic'),
            'price' => Yii::t('app', 'Price'),
            'special_price' => Yii::t('app', 'Special Price'),
            'slug' => Yii::t('app', 'Slug'),
            'expected_delivery' => Yii::t('app', 'Expected Delivery'),
            'sort_number' => Yii::t('app', 'Sort Number'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function extraFields()
    {
        $fields = parent::extraFields ();

        $fields['paymentMethods'] =  function ($model) {
            return PaymentMethod::find ()
                ->andWhere (['in', 'payment_method_id', ['1', '2']])
                ->all ();
        };

        $fields['formatedPrice'] = function ($model) {

            //$store = \Yii::$app->accountManager->getManagedAccount();
            //$store->currency->code

            $price = $model->special_price > 0? $model->special_price: $model->price;

            return \Yii::$app->formatter->asCurrency($price, 'KWD');
        };

        return $fields;
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy($modelClass = "\common\models\Admin")
    {
        return $this->hasOne($modelClass::className(), ['admin_id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy($modelClass = "\common\models\Admin")
    {
        return $this->hasOne($modelClass::className(), ['admin_id' => 'updated_by']);
    }

    /**
     * Gets query for [[AddonPayments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddonPayments($modelClass = "\common\models\AddonPayment")
    {
        return $this->hasMany($modelClass::className(), ['addon_uuid' => 'addon_uuid']);
    }

    /**
     * Gets query for [[RestaurantAddons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantAddons($modelClass = "\common\models\RestaurantAddon")
    {
        return $this->hasMany($modelClass::className(), ['addon_uuid' => 'addon_uuid']);
    }

    /**
     * @return query\AddonQuery
     */
    public static function find()
    {
        return new query\AddonQuery(get_called_class());
    }
}
