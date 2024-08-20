<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_domain_request".
 *
 * @property string $request_uuid
 * @property string $restaurant_uuid
 * @property string|null $domain
 * @property int|null $status
 * @property int|null $created_by
 * @property string|null $expire_at
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Agent $createdBy
 * @property Restaurant $restaurant
 */
class RestaurantDomainRequest extends \yii\db\ActiveRecord
{
    /**
     * pre-owned
     * --------------------------
     * assigned - assign directly
     *
     * purchase request
     * --------------------------
     * pending
     * purchased - staff will purchase from godaddy account
     * assigned - assign domain to store in netlify
     */

    const STATUS_ASSIGNED = 1;
    const STATUS_PENDING = 2;
    const STATUS_PURCHASED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_domain_request';
    }

    public static function arrStatus()
    {
        return [
            self::STATUS_PENDING => "pending",
            self::STATUS_PURCHASED => "purchased",
            self::STATUS_ASSIGNED => "assigned"
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['request_uuid'], 'required'],
            [['status', 'created_by'], 'integer'],
            [['created_at', 'updated_at', 'expire_at'], 'safe'],
            [['request_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['domain'], 'string', 'max' => 255],
            [['request_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['created_by' => 'agent_id']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'request_uuid',
                ],
                'value' => function () {
                    if (!$this->request_uuid)
                        $this->request_uuid = 'request_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->request_uuid;
                }
            ],
            /*[
                'class' => BlameableBehavior::className()
            ],*/
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value' => function() {
                    if($this->created_at)
                        return $this->created_at;

                    return new Expression('NOW()');
                },
            ],
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if (!$insert && isset($changedAttributes['status']) && $this->status == self::STATUS_ASSIGNED) {

            $oldDomain = $this->restaurant->restaurant_domain;

            $this->restaurant->restaurant_domain = $this->domain;
            $this->restaurant->save(false);

            $this->restaurant->notifyDomainUpdated($oldDomain, $this);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'request_uuid' => Yii::t('app', 'Request Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Store Uuid'),
            'domain' => Yii::t('app', 'Domain'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'expire_at' => Yii::t('app', 'Expire By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[AgentName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgentName($modelClass = "\common\models\Agent")
    {
        if($this->createdBy)
            return $this->createdBy->name;
    }

    /**
     * Gets query for [[RestaurantName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreName($modelClass = "\common\models\Restaurant")
    {
        if($this->restaurant)
            return $this->restaurant->name;
    }

    /**
     * Gets query for [[RestaurantName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantname($modelClass = "\common\models\Restaurant")
    {
        if($this->restaurant)
            return $this->restaurant->name;
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

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy($modelClass = "\common\models\Agent")
    {
        return $this->hasOne($modelClass::className(), ['agent_id' => 'created_by']);
    }

    /**
     * @return query\RestaurantDomainRequestQuery
     */
    public static function find() {
        return new query\RestaurantDomainRequestQuery(get_called_class());
    }
}
