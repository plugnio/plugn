<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "queue".
 *
 * @property int $queue_id
 * @property string $restaurant_uuid
 * @property int|null $queue_status
 * @property string|null $queue_created_at
 * @property string|null $queue_updated_at
 * @property string|null $queue_start_at
 * @property string|null $queue_end_at
 *
 * @property Restaurant $restaurantUu
 */
class Queue extends \yii\db\ActiveRecord {

    //Values for `queue_status`
    const QUEUE_STATUS_PENDING = 1;
    const QUEUE_STATUS_CREATING = 2;
    const QUEUE_STATUS_COMPLETE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'queue_status'], 'required'],
            [['queue_status'], 'integer'],
            [['queue_start_at', 'queue_end_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'queue_created_at',
                'updatedAtAttribute' => 'queue_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert) {

        if ($this->queue_status == self::QUEUE_STATUS_PENDING) {



            $store_model = $this->restaurant;

            $getLatestCommitResponse = Yii::$app->githubComponent->getLastCommit();

            if ($getLatestCommitResponse->isOk) {


                $sha = $getLatestCommitResponse->data['sha'];

                //Replace test with store branch name
                $branchName = 'refs/heads/' . $store_model->store_branch_name;
                $createBranchResponse = Yii::$app->githubComponent->createBranch($sha, $branchName);

                if ($createBranchResponse->isOk) {

                   $url = parse_url($store_model->restaurant_domain);

                  Yii::$app->netlifyComponent->createSite($url['host'], $store_model->store_branch_name;);


                //
                //
                //    $url = parse_url($store_model->restaurant_domain);
                //     $createNewSiteResponse = Yii::$app->netlifyComponent->createSite($url['host'], $store_model->store_branch_name);
                //
                //         if ($createNewSiteResponse->isOk) {
                //
                //             $site_id = $createNewSiteResponse->data['site_id'];
                //             $store_model->site_id = $site_id;
                //             $store_model->save(false);
                //
                //         } else {
                //             Yii::error('[Netlify > While Creating new site]' . json_encode($createNewSiteResponse->data), __METHOD__);
                //             return false;
                //         }
                //
                // } else{
                //   Yii::error('[Github > Create branch]' . json_encode($createBranchResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid, __METHOD__);
                //   return false;
                }

            } else {
                Yii::error('[Github > Last commit]' . json_encode($getLatestCommitResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid, __METHOD__);
                return false;
            }

            $this->queue_status = Queue::QUEUE_STATUS_COMPLETE;

        }
        return parent::beforeSave($insert);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'queue_id' => 'Queue ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'queue_status' => 'Queue Status',
            'queue_created_at' => 'Queue Created At',
            'queue_updated_at' => 'Queue Updated At',
            'queue_start_at' => 'Queue Start At',
            'queue_end_at' => 'Queue End At',
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
