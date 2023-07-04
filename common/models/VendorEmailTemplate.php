<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "vendor_email_template".
 *
 * @property string $template_uuid
 * @property string|null $subject
 * @property string|null $message
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property CustomerCampaign[] $customerCampaigns
 * @property VendorCampaign[] $vendorCampaigns
 */
class VendorEmailTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vendor_email_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['template_uuid'], 'required'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['template_uuid'], 'string', 'max' => 60],
            [['subject'], 'string', 'max' => 255],
            [['template_uuid'], 'unique'],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'template_uuid',
                ],
                'value' => function () {
                    if (!$this->template_uuid) {
                        $this->template_uuid = 'template_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->template_uuid;
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
            'template_uuid' => Yii::t('app', 'Template Uuid'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[VendorCampaigns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendorCampaigns($modelClass = "\common\models\VendorCampaign")
    {
        return $this->hasMany($modelClass::className(), ['template_uuid' => 'template_uuid']);
    }
}
