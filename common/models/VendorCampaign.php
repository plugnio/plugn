<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\console\Exception;
use yii\db\Expression;
use yii\helpers\Console;

/**
 * This is the model class for table "vendor_campaign".
 *
 * @property string $campaign_uuid
 * @property string $template_uuid
 * @property int|null $progress
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property VendorEmailTemplate $template
 */
class VendorCampaign extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_READY = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vendor_campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_uuid'], 'required'],
            [['progress', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['campaign_uuid', 'template_uuid'], 'string', 'max' => 60],
            [['campaign_uuid'], 'unique'],
            [['template_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => VendorEmailTemplate::className(), 'targetAttribute' => ['template_uuid' => 'template_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'campaign_uuid',
                ],
                'value' => function () {
                    if (!$this->campaign_uuid) {
                        $this->campaign_uuid = 'campaign_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->campaign_uuid;
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

    public function getStatusName() {
        switch ($this->status) {
            case 0:
                return 'Draft';
            case 1:
                return 'In Process';
            case 2:
                return 'Completed';
            case 3:
                return 'In Queue';

        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'campaign_uuid' => Yii::t('app', 'Campaign Uuid'),
            'template_uuid' => Yii::t('app', 'Template Uuid'),
            'progress' => Yii::t('app', 'Progress (%)'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * process campaign
     * @return void
     */
    public function process() {

        $this->status = self::STATUS_IN_PROGRESS;

        if(!$this->save()) {
            throw new Exception(print_r($this->errors, true));
        }

        $query = Restaurant::find()
            ->andWhere(['=', 'restaurant.is_deleted', 0]);

        $filters = $this->getCampaignFilters()->all();

        foreach ($filters as $filter) {
            if ($filter['param'] == "warned_delete_at")
            {
                $query->andWhere([
                    'warned_delete_at' => date('Y-m-d', strtotime($filter['value']))
                ]);
            }
            else if (in_array($filter['param'], [
                "enable_debugger",
                "is_deleted",
                "is_under_maintenance",
                "is_sandbox",
                "accept_order_247",
                "is_public",
                "is_tap_enable",
                "is_myfatoorah_enable",
                "support_delivery",
                "support_pick_up",
                "not_for_profit"
            ])) {
                $query->andWhere([$filter['param'] => true]);
            } else {
                $query->andWhere([$filter['param'] => $filter['value']]);
            }
        }

        $total = $query->count();

        $processed = 0;

        foreach ($query->batch(100) as $stores) {

            foreach ($stores as $store) {
                try {
                    $store->sendVendorEmailTemplate($this);
                } catch (\Exception $e) {
                    Yii::error($e, 'campaign');
                    continue;
                }
            }

            $processed += sizeof($stores);

            $this->progress = ceil($processed * 100 / $total);

            if(!$this->save()) {
                throw new Exception(print_r($this->errors, true));
            }

            sleep(10);
        }

        $this->status = self::STATUS_COMPLETED;

        if(!$this->save()) {
            throw new Exception(print_r($this->errors, true));
        }
    }

    /**
     * Gets query for [[CampaignFilters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignFilters($modelClass = "\common\models\CampaignFilter")
    {
        return $this->hasOne($modelClass::className(), ['campaign_uuid' => 'campaign_uuid']);
    }

    /**
     * Gets query for [[Template]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate($modelClass = "\common\models\VendorEmailTemplate")
    {
        return $this->hasOne($modelClass::className(), ['template_uuid' => 'template_uuid']);
    }
}
