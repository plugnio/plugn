<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "store_domain_subscription".
 *
 * @property string $subscription_uuid
 * @property string|null $restaurant_uuid
 * @property string $domain_registrar
 * @property string $domain
 * @property string $from
 * @property string $to
 * @property int $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Admin $createdBy
 * @property Restaurant $restaurantUu
 * @property Admin $updatedBy
 */
class StoreDomainSubscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_domain_subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //'subscription_uuid',, 'created_by', 'created_at'
            [["restaurant_uuid", 'domain_registrar', 'domain', 'from', 'to'], 'required'],
            [['from', 'to', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['subscription_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['domain_registrar', 'domain'], 'string', 'max' => 100],
            [['subscription_uuid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::class, 'targetAttribute' => ['created_by' => 'admin_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::class, 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::class, 'targetAttribute' => ['updated_by' => 'admin_id']],
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'subscription_uuid',
                ],
                'value' => function() {
                    if (!$this->subscription_uuid)
                        $this->subscription_uuid = 'sub_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->subscription_uuid;
                }
            ],
            [
                'class' => BlameableBehavior::className()
            ],
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => "updated_at",
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
            'subscription_uuid' => Yii::t('app', 'Subscription Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'domain_registrar' => Yii::t('app', 'Domain Registrar'),
            'domain' => Yii::t('app', 'Domain'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * notify before 2 week
     * @return void
     */
    public static function notifyAboutToExpire() {

        $query = StoreDomainSubscription::find()
            ->andWhere(new Expression("DATE(`to`) = DATE(NOW() + INTERVAL 14 DAY)"))
            ->with(['restaurant']);

        foreach ($query->batch() as $subscriptions) {

            foreach ($subscriptions as $subscription) {

                $agents = $subscription->restaurant->getOwnerAgent()->all();

                $agentEmails = ArrayHelper::getColumn($agents, 'agent_email');

                $ml = new MailLog();
                $ml->to = implode(',', $agentEmails);
                $ml->from = \Yii::$app->params['noReplyEmail'];
                $ml->subject = 'Your Domain is Expiring';
                $ml->save();

                $mailer = \Yii::$app->mailer->compose([
                    'html' => 'store/domain-will-expire-soon-html',
                ], [
                        'subscription' => $subscription,
                        'store' => $subscription->restaurant
                    ])
                    ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
                    //->setReplyTo(\Yii::$app->params['supportEmail'])
                    //->setTo($agents[0]->agent_email)
                    //->setCc(array_slice($agents, 1))
                    ->setTo($agentEmails)
                    ->setBcc(\Yii::$app->params['supportEmail'])
                    ->setSubject('Your Subscription is Expiring');

                if(\Yii::$app->params['elasticMailIpPool'])
                    $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

                try {
                    $mailer->send();
                } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                    // Handle email transport-specific exceptions
                    Yii::error( "Failed to send email: " . $e->getMessage());
                } catch (\Exception $e) {
                    // Handle any other exceptions
                    Yii::error( "An error occurred: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * @return null|string
     */
    public function getRestaurantName() {
        return $this->restaurant ? $this->restaurant->name: null;
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Admin::class, ['admin_id' => 'created_by']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::class, ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Admin::class, ['admin_id' => 'updated_by']);
    }
}
