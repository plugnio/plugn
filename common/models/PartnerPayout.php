<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "partner_payout".
 *
 * @property string $partner_payout_uuid
 * @property string $partner_uuid
 * @property string $payout_status
 * @property float|null $amount
 * @property int $created_at
 * @property int $updated_at
 * @property int $bank_id
 * @property int $transfer_benef_iban
 * @property string $transfer_benef_name
 * @property string $transfer_file

 * @property Partner $partners
 * @property Payment $payments
 */
class PartnerPayout extends \yii\db\ActiveRecord
{

    //Values for `payout_status`
    const PAYOUT_STATUS_PENDING = 0;
    const PAYOUT_STATUS_UNPAID = 1;
    const PAYOUT_STATUS_PAID = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner_payout';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['partner_uuid','transfer_benef_iban','transfer_benef_name','bank_id'], 'required'],
            [['amount'], 'number'],
            ['payout_status', 'in', 'range' => [self::PAYOUT_STATUS_UNPAID, self::PAYOUT_STATUS_PAID,self::PAYOUT_STATUS_PENDING]],
            [['partner_payout_uuid', 'partner_uuid'], 'string', 'max' => 60],
            [['transfer_file'], 'string', 'max' => 255],
            [['partner_payout_uuid'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['partner_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::className(), 'targetAttribute' => ['partner_uuid' => 'partner_uuid']],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'partner_payout_uuid' => 'Partner Payout Uuid',
            'partner_uuid' => 'Partner Uuid',
            'amount' => 'Amount',
            'payout_status' => 'Payout Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * Return Civil id back side url
     * @return string
     */
    public function getTransferFile()
    {

      $url  = '';
        if ($this->transfer_file) {

            $url = 'https://res.cloudinary.com/plugn/raw/upload/transfer-files/'
                . $this->transfer_file;

        }

        return $url;
    }


    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);


          $payments = $this->partner->getPayments()->all();

          if($payments){
            foreach ($payments as $key => $payment) {
              $payment->payout_status = $this->payout_status;
              $payment->save(false);
            }
          }

          $subscriptionPayments = $this->partner->getSubscriptionPayments()->all();

          if($subscriptionPayments){
            foreach ($subscriptionPayments as $key => $payment) {
              $payment->payout_status = $this->payout_status;
              $payment->save(false);
            }
          }

    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'partner_payout_uuid',
                ],
                'value' => function() {
                    if (!$this->partner_payout_uuid)
                        $this->partner_payout_uuid = 'part_payout_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->partner_payout_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }


      /**
       * Returns String value of current status
       * @return string
       */
      public function getStatus(){
          switch($this->payout_status){
              case self::PAYOUT_STATUS_UNPAID:
                  return "Unpaid";
                  break;
              case self::PAYOUT_STATUS_PENDING:
                  return "Pending";
                  break;
              case self::PAYOUT_STATUS_PAID:
                  return "Paid";
                  break;
          }

          return "Couldnt find a status";
      }



    public static function getPayablePartnerListFormat()
    {
        $totalAmount = 0;

        $transferPartners = self::find()
            ->where(['payout_status' => self::PAYOUT_STATUS_PENDING])
            ->all();

        if (!$transferPartners) {
            return false;
        }

        $list = [];


        foreach ($transferPartners as $transferPartner) {

            $partner = $transferPartner->partner;

            if (
                empty($partner->bank) ||
                !$transferPartner->bank_id ||
                !$transferPartner->transfer_benef_iban ||
                !$transferPartner->transfer_benef_name
            ) {
                continue;
            }

            $totalAmount += $transferPartner->amount;

            $list[] = [
                'transfer' => 'S2',
                'bank_transfer_type' => $transferPartner->bank->bank_transfer_type,
                'amount' => number_format($transferPartner->amount, 3, '.', ''),
                'currency' => 'KWD',
                'emptyField1' => '',
                'emptyField2' => '',
                'emptyField3' => '',
                'Field1' => '11622216',
                'iban' => ltrim(rtrim($transferPartner->transfer_benef_iban )),
                'transfer_id' => $transferPartner->partner_payout_uuid,
                'tc_id' => $transferPartner->partner_payout_uuid,
                'description' => 'Referral commission',
                'emptyField4' => '',
                'emptyField5' => '',
                'emptyField6' => '',
                'bank_account_name' => ltrim(rtrim($transferPartner->transfer_benef_name)),
                'bank_name' => $transferPartner->bank->bank_name,
                'emptyField7' => '',
                'bank_name_repeat' => $transferPartner->bank->bank_name,
                'bank_address' => $transferPartner->bank->bank_address,
                'emptyField8' => '',
                'emptyField9' => '',
                'bank_swift_code' => $transferPartner->bank->bank_swift_code,
                'emptyField10' => '',
                'emptyField11' => '',
                'emptyField12' => '',
                'emptyField13' => '',
                'emptyField14' => '',
                'emptyField15' => '',
                'Field2' => 'B',
                'emptyField16' => '',
                'emptyField17' => '',
                'partner_iban' => ltrim(rtrim($transferPartner->transfer_benef_iban))
            ];
        }

        return [
            'partner_list' => $list,
            'total_amount' => number_format($totalAmount, 3, '.', ''),
        ];
    }



      /**
       * sending notification to all partners with
       * unpaid transfer due to bank issue
       * @return bool
       */
      // public function unpaidNotification()
      // {
      //     $tmpName = explode(" ",$this->partner->username);
      //
      //     Yii::$app->mailer->htmlLayout = 'layouts/html';
      //
      //     return Yii::$app->mailer->compose("candidate/transfer-fail.php",
      //         [
      //             "name" => (isset($tmpName[0]))  ? $tmpName[0] : $this->candidate->candidate_name,
      //             'logo' => Url::to('@web/images/logo.png', true),
      //             "webUrl" => Yii::$app->params['candidateAppUrl'] . 'view/payments',
      //         ])
      //         ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['appName']])
      //         ->setTo($this->candidate->candidate_email)
      //         ->setBcc(Yii::$app->params['supportEmail'])
      //         ->setSubject('Transfer failed. Please update your bank info')
      //         ->send();
      // }


    /**
     * Gets query for [[PartnerUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['partner_uuid' => 'partner_uuid']);
    }


      /**
       * Gets query for [[Bank]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getBank()
      {
          return $this->hasOne(Bank::className(), ['bank_id' => 'bank_id']);
      }


    /**
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['partner_payout_uuid' => 'partner_payout_uuid']);
    }

    /**
     * Gets query for [[SubscriptionPayments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::className(), ['partner_payout_uuid' => 'partner_payout_uuid']);
    }

}
