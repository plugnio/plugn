<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_ingredient".
 *
 * @property string $ingredient_uuid
 * @property string $restaurant_uuid
 * @property string $name Name of the ingredient (e.g., "Wheat Bread")
 * @property int|null $stock_quantity Total stock of the ingredient available
 * @property string|null $image_url URL pointing to the ingredient’s image
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Restaurant $restaurant
 * @property RestaurantInventory[] $restaurantInventories
 */
class RestaurantIngredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'name'], 'required'],//'ingredient_uuid', 'created_at', 'updated_at'
            [['stock_quantity'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ingredient_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['name', 'image_url'], 'string', 'max' => 255],
            [
                ['image_url'],
                '\common\components\S3FileExistValidator',
                'filePath' => '',
                'message' => Yii::t('app', "Please upload image"),
                'resourceManager' => Yii::$app->temporaryBucketResourceManager,
                'when' => function ($model, $attribute) {
                    return $model->{$attribute} !== $model->getOldAttribute($attribute);
                }
            ],
            [['ingredient_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->image_url != $this->getOldAttribute("image_url")) {

            if ($this->getOldAttribute("image_url")) {
                if (!$this->deleteImage($this->getOldAttribute("image_url"))) {
                    return false;
                }
            }

            if ($this->image_url) {
                if (!$this->saveImage()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if (!$this->deleteImage($this->image_url)) {
            return false;
        }

        return true;
    }

    /**
     * @param $image_url
     * @return false|void
     */
    public function deleteImage($image_url) {

        try {

            Yii::$app->resourceManager->delete($image_url);

        } catch (\Aws\S3\Exception\S3Exception $e) {

            Yii::error($e->getMessage(), 'agent');

            $this->addError('image_url', Yii::t('app', 'Please try again.'));

            return false;

        } catch (\Exception $e) {

            Yii::error($e->getMessage(), 'agent');

            $this->addError('image_url', $e->getMessage());

            return false;
        }
    }

    /**
     * Upload image to permanent s3 bucket
     */
    public function saveImage()
    {
        if (!$this->image_url) {
            $this->addError ('image_url', Yii::t ('app', 'Image not available to save.'));
            return false;
        }

        try {
            $sourceBucket = Yii::$app->temporaryBucketResourceManager->bucket;

            $extension = pathinfo($this->image_url, PATHINFO_EXTENSION);

            $file_s3_path = 'ingredient/' . $this->restaurant_uuid . "/" .
                Yii::$app->security->generateRandomString() . '.' . $extension;

            Yii::$app->resourceManager->copy($this->image_url, $file_s3_path, $sourceBucket);

            $this->image_url = $file_s3_path;

        } catch (\Aws\S3\Exception\S3Exception $e) {

            Yii::error($e->getMessage(), 'agent');

            $this->addError('image_url', Yii::t('app', 'Please try again.'));

            return false;

        } catch (\Exception $e) {

            Yii::error($e->getMessage(), 'agent');

            $this->addError('image_url', $e->getMessage());

            return false;
        }
    }

    //update inventory afterSave

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $model = new RestaurantInventory;
            $model->restaurant_uuid = $this->restaurant_uuid;
            $model->ingredient_uuid = $this->ingredient_uuid;
        } else {
            $model = RestaurantInventory::find()
                ->andWhere(['ingredient_uuid' => $this->ingredient_uuid])
                ->one();

            if (!$model) {
                $model = new RestaurantInventory;
                $model->restaurant_uuid = $this->restaurant_uuid;
                $model->ingredient_uuid = $this->ingredient_uuid;
            }
        }

        $model->stock_quantity = $this->stock_quantity;
        if (!$model->save()) {
            Yii::error(print_r($model->errors, true));
        }

        return true;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'ingredient_uuid',
                ],
                'value' => function () {
                    if (!$this->ingredient_uuid) {
                        $this->ingredient_uuid = 'ingredient_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->ingredient_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
            'ingredient_uuid' => Yii::t('app', 'Ingredient Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'name' => Yii::t('app', 'Name'),
            'stock_quantity' => Yii::t('app', 'Stock Quantity'),
            'image_url' => Yii::t('app', 'Image Url'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($model = 'common\models\Restaurant')
    {
        return $this->hasOne($model::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantInventories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantInventories($model = 'common\models\RestaurantInventory')
    {
        return $this->hasMany($model::className(), ['ingredient_uuid' => 'ingredient_uuid']);
    }
}
