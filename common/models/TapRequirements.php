<?php

namespace common\models;

use borales\extensions\phoneInput\PhoneInputBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "tap_requirements".
 *
 * @property string $tap_requirements_uuid
 * @property int|null $country_id
 * @property string|null $requirement_en
 * @property string|null $requirement_ar
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Country $country
 */
class TapRequirements extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tap_requirements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requirement_en', 'requirement_ar', 'country_id'], 'required'],
            [['country_id'], 'integer'],
            [['requirement_en', 'requirement_ar'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['tap_requirements_uuid'], 'string', 'max' => 60],
            [['tap_requirements_uuid'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'tap_requirements_uuid',
                ],
                'value' => function () {
                    if (!$this->tap_requirements_uuid)
                        $this->tap_requirements_uuid = 'tap_requirements_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->tap_requirements_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
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
            'tap_requirements_uuid' => Yii::t('app', 'Tap Requirements Uuid'),
            'country_id' => Yii::t('app', 'Country ID'),
            'requirement_en' => Yii::t('app', 'Requirement - English'),
            'requirement_ar' => Yii::t('app', 'Requirement - Arabic'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }
}
