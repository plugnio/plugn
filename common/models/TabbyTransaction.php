<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * This is the model class for table "tabby_transaction".
 *
 * @property int $id
 * @property string|null $body
 * @property string $order_uuid
 * @property string $status
 * @property string $source
 * @property string $transaction_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $orderUu
 */
class TabbyTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tabby_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_uuid', 'status', 'source', 'transaction_id'], 'required'],
            [['id'], 'integer'],
            [['body'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_uuid'], 'string', 'max' => 40],
            [['status', 'source'], 'string', 'max' => 16],
            [['transaction_id'], 'string', 'max' => 64],
            [['id', 'order_uuid', 'transaction_id'], 'unique', 'targetAttribute' => ['id', 'order_uuid', 'transaction_id']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'body' => Yii::t('app', 'Body'),
            'order_uuid' => Yii::t('app', 'Order Uuid'),
            'status' => Yii::t('app', 'Status'),
            'source' => Yii::t('app', 'Source'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function getIsConfigured($restaurant_uuid) {
        //$status = trim(Setting::getConfig($restaurant_uuid, PaymentMethod::CODE_TABBY, 'payment_tabby_status'));
        $public_key = trim(Setting::getConfig($restaurant_uuid, PaymentMethod::CODE_TABBY, 'payment_tabby_public_key'));
        $secret_key = trim(Setting::getConfig($restaurant_uuid, PaymentMethod::CODE_TABBY, 'payment_tabby_secret_key'));

        return (
          //  $status == 1 &&
            $public_key &&
            $secret_key
        );
    }

    /**
     * @param $order_uuid
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getTransaction($order_uuid) {
        return self::find()
            ->orderBy("created_at DESC")
            ->andWhere(['order_uuid' => $order_uuid])
            ->one();
    }

    /**
     * @param $order_uuid
     * @return array
     */
    public function getTransactions($order_uuid) {

        $txn = self::getTransaction($order_uuid);

        $transactions = [];

        if ($txn && !empty($txn['body'])) {
            $txn = json_decode($txn['body']);
            $transactions[] = [
                "id"        => $txn->id,
                "order_uuid"  => $order_uuid,
                "type"      => 'AUTH',
                "amount"    => $txn->amount,
                "currency"  => $txn->currency,
                "date"      => $txn->created_at
            ];
            foreach ($txn->captures as $capture) {
                $transactions[] = [
                    "id"        => $capture->id,
                    "payment_id"=> $txn->id,
                    "order_uuid"  => $order_uuid,
                    "type"      => 'CAPTURE',
                    "amount"    => $capture->amount,
                    "currency"  => $txn->currency,
                    "date"      => $capture->created_at
                ];
            }
            foreach ($txn->refunds as $refund) {
                $transactions[] = [
                    "id"        => $refund->id,
                    "payment_id"=> $txn->id,
                    "capture_id"=> $refund->capture_id,
                    "order_uuid"  => $order_uuid,
                    "type"      => 'REFUND',
                    "amount"    => $refund->amount,
                    "currency"  => $txn->currency,
                    "date"      => $refund->created_at
                ];
            }
        }

        return $transactions;
    }

    /**
     * @param $restaurant_uuid
     * @return void
     */
    public static function registerWebhooks($restaurant_uuid) {
        
        $key = Setting::getConfig($restaurant_uuid, PaymentMethod::CODE_TABBY, 'payment_tabby_secret_key');

        $sk =  trim($key);
        if (empty($sk)) return;
        $codes = array('AE', 'SA', 'KW', 'BH', 'QA');

        $url = Url::to(['payment/tabby/callback'], true);

        $is_test = (bool)preg_match("#^sk_test_#", $sk);
        foreach ($codes as $code) {
            $check_webhook = false;
            $webhooks = json_decode(self::exec($restaurant_uuid, 'GET', $code), true);
            self::ddlog('info', 'Checking webhooks for ' . $code, null, ['webhooks' => $webhooks]);
            if (array_key_exists('status', $webhooks) && $webhooks['status'] == 'error') continue;
            foreach ($webhooks as $webhook) {
                if (isset($webhook['url']) && $webhook['url'] == $url) {
                    if ($webhook['is_test'] != $is_test) {
                        self::ddlog('info', 'Updating webhook for ' . $code, null, [
                            'code'      => $code,
                            'url'       => $url,
                            'is_test'   => $is_test,
                            'id'        => $webhook['id']
                        ]);
                        self::exec($restaurant_uuid, 'PUT', $code, ['url' => $url, 'is_test' => $is_test], $webhook['id']);
                    }
                    $check_webhook = true;
                }
            }
            if (!$check_webhook) {
                self::ddlog('info', 'Creating webhook for ' . $code, null, [
                    'code'      => $code,
                    'url'       => $url,
                    'is_test'   => $is_test
                ]);
                self::exec($restaurant_uuid, 'POST', $code, ['url' => $url, 'is_test' => $is_test]);
            }
        }
    }

    public static function exec($restaurant_uuid, $method, $code, $data = null, $id = null) {

        $key = Setting::getConfig($restaurant_uuid, PaymentMethod::CODE_TABBY, 'payment_tabby_secret_key');

        $authorization = 'Authorization: Bearer ' . trim($key);
        $merchant_code = 'X-Merchant-Code: ' . $code;
        $headers = array($merchant_code, $authorization);

        if ($method != 'GET') {
            $headers[] = 'Content-Type: application/json';
        }

        $curl = curl_init();

        $curl_options = array(
            CURLOPT_URL => 'https://api.tabby.ai/api/v1/webhooks' . ($id ? '/' . $id : ''),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        );
        if ($method != 'GET') {
            $curl_options[CURLOPT_POSTFIELDS] = json_encode($data);;
        }

        curl_setopt_array($curl, $curl_options);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * todo: log to separate db table to show to vendor/ admin
     * @param $status
     * @param $message
     * @param $e
     * @param $data
     * @return void
     */
    public static function ddlog($status = "error", $message = "Something went wrong", $e = null, $data = null) {
        Yii::info($status .": " .  $message);
        Yii::info($e);
        Yii::info($data);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($model = 'common\models\Order')
    {
        return $this->hasOne($model::className(), ['order_uuid' => 'order_uuid']);
    }
}
