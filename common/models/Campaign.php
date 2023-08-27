<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "campaign".
 *
 * @property string $utm_uuid
 * @property string|null $restaurant_uuid
 * @property string|null $utm_source e.g. newsletter, twitter, google, etc.
 * @property string|null $utm_medium e.g. email, social, cpc, etc.
 * @property string|null $utm_campaign e.g. promotion, sale, etc.
 * @property string|null $utm_content Any call-to-action or headline, e.g. buy-now.
 * @property string|null $utm_term Keywords for your paid search campaigns
 * @property number|null $investment
 * @property number|null $no_of_signups
 * @property number|null $no_of_clicks
 * @property number|null $no_of_stores
 * @property number|null $no_of_orders
 * @property number|null $total_commission
 * @property number|null $total_gateway_fee
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Restaurant $restaurantUu
 */
class Campaign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['utm_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'], 'string', 'max' => 100],
            [['utm_uuid'], 'unique'],
            [['investment', 'no_of_signups', 'no_of_clicks', 'no_of_stores', 'no_of_orders', 'total_commission', 'total_gateway_fee'], 'number'],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'utm_uuid',
                ],
                'value' => function () {
                    if (!$this->utm_uuid)
                        $this->utm_uuid = 'utm_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->utm_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'utm_uuid' => Yii::t('app', 'Utm Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'utm_source' => Yii::t('app', 'Utm Source'),
            'utm_medium' => Yii::t('app', 'Utm Medium'),
            'utm_campaign' => Yii::t('app', 'Utm Campaign'),
            'utm_content' => Yii::t('app', 'Utm Content'),
            'utm_term' => Yii::t('app', 'Utm Term'),
            'investment' => Yii::t('app', 'Investment'),
            'no_of_signups' => Yii::t('app', 'Total signups'),
            'no_of_clicks'=> Yii::t('app', 'Total clicks'),
            'no_of_stores' => Yii::t('app', 'Total Stores'),
            'no_of_orders' => Yii::t('app', 'Total Orders'),
            'total_commission' => Yii::t('app', 'Total Commission'),
            'total_gateway_fee' => Yii::t('app', 'Total Gateway Fee'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array|false|int[]|string[]
     */
    public function extraFields()
    {
        $fields = parent::extraFields();

        $fields['campaignChartData'] = function() {
            return $this->campaignChartData();
        };

        return $fields;
    }

    /**
     * return campaign usage by months
     * @return array
     */
    public function campaignChartData() {

        $campaign_chart_data = [];

        /*$date_start = $this->valid_from;

        if(strtotime($this->valid_until) < time()) {
            $date_end = $this->valid_until;
        } else {
            $date_end = date('Y') . '-' . date('m') . '-1';
        }

        $months = $this->getMonthsBetween($date_start, $date_end);

        for ($i = 0; $i < $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $voucher_chart_data[$month] = array(
                'month'   => date('F', strtotime('-'.($months - $i).' month')),
                'total' => 0
            );
        }*/

        $rows = $this->getOrders()
            ->activeOrders()
            ->select ('order_created_at, COUNT(*) as total')
            //->andWhere('DATE(`order_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_created_at`) < DATE("'.$date_end.'")')
            ->groupBy (new Expression('MONTH(order_created_at), YEAR(order_created_at)'))
            ->orderBy('order_created_at')
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $campaign_chart_data[date ('m', strtotime ($result['order_created_at']))] = array(
                'month' => Yii::t('app', date ('M', strtotime ($result['order_created_at']))),
                'total' => (int) $result['total']
            );
        }

        return array_values($campaign_chart_data);
    }

    /**
     * Gets query for [[RestaurantByCampaigns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantByCampaigns()
    {
        return $this->hasMany(RestaurantByCampaign::className(), ['utm_uuid' => 'utm_uuid']);
    }

    public function getAgents()
    {
        return $this->hasMany(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Gets query for [[StoresByCampaigns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoresByCampaigns()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->via('restaurantByCampaigns');
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['utm_uuid' => 'utm_uuid']);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
