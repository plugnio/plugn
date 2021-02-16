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

          Yii::info('[beforeSave]', __METHOD__);
          Yii::info('[Queue] => ' .   $this->queue_id, __METHOD__);
          Yii::info('[Queue Status] => ' .   $this->queue_status, __METHOD__);

            // $store_model = $this->restaurant;
            //
            // $getLastCommitResponse = Yii::$app->githubComponent->getLastCommit();
            //
            // if ($getLastCommitResponse->isOk) {
            //
            //     $sha = $getLastCommitResponse->data['sha'];
            //
            //     //Replace test with store branch name
            //     $branchName = 'refs/heads/' . $store_model->store_branch_name;
            //     $createBranchResponse = Yii::$app->githubComponent->createBranch($sha, $branchName);
            //
            //     if (!$createBranchResponse->isOk) {
            //
            //
            //
            //        // $url = parse_url($store_model->restaurant_domain);
            //        //  $createNewSiteResponse = Yii::$app->netlifyComponent->createSite($url['host'], $store_model->store_branch_name);
            //        //
            //        //      if ($createNewSiteResponse->isOk) {
            //        //
            //        //          $site_id = $createNewSiteResponse->data['site_id'];
            //        //          $store_model->site_id = $site_id;
            //        //          $store_model->save(false);
            //        //
            //        //      } else {
            //        //          Yii::error('[Netlify > While Creating new site]' . json_encode($createNewSiteResponse->data), __METHOD__);
            //        //          $this->deleteBuildJsFolder();
            //        //          return false;
            //        //      }
            //        //
            //
            //         // $fileToBeUploaded = file_get_contents("store/" . $store_model->store_branch_name . "/build.js");
            //         //
            //         // // Encode the image string data into base64
            //         // $data = base64_encode($fileToBeUploaded);
            //
            //         //Replace test with store branch name
            //         // $commitBuildJsFileResponse = Yii::$app->githubComponent->createFileContent($data, $store_model->store_branch_name, 'build.js');
            //         //
            //         // if ($commitBuildJsFileResponse->isOk) {
            //         //
            //         //     //Replace test with store domain name
            //         //     $url = parse_url($store_model->restaurant_domain);
            //         //     $createNewSiteResponse = Yii::$app->netlifyComponent->createSite($url['host'], $store_model->store_branch_name);
            //         //
            //         //     if ($createNewSiteResponse->isOk) {
            //         //
            //         //         $site_id = $createNewSiteResponse->data['site_id'];
            //         //         $store_model->site_id = $site_id;
            //         //         $store_model->save(false);
            //         //
            //         //     } else {
            //         //         Yii::error('[Netlify > While Creating new site]' . json_encode($createNewSiteResponse->data), __METHOD__);
            //         //         $this->deleteBuildJsFolder();
            //         //         return false;
            //         //     }
            //         // } else {
            //         //   Yii::error('[Github > Commit build JS]' . json_encode($commitBuildJsFileResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid, __METHOD__);
            //         //   $this->deleteBuildJsFolder();
            //         //   return false;
            //         // }
            //     // } else{
            //       Yii::error('[Github > Create branch]' . json_encode($createBranchResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid, __METHOD__);
            //       $this->deleteBuildJsFolder();
            //       return false;
            //     }
            //
            // } else {
            //     Yii::error('[Github > Last commit]' . json_encode($getLastCommitResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid, __METHOD__);
            //     $this->deleteBuildJsFolder();
            //     return false;
            // }

            \Yii::$app->netlifyComponent->createSite('angular.plugn.store', 'angularadvance');
            $this->queue_status = Queue::QUEUE_STATUS_COMPLETE;


            // $this->deleteBuildJsFolder();
        }
        return parent::beforeSave($insert);
    }


    // public function deleteBuildJsFolder(){
    //
    //   $dirPath = "store/" .  $this->restaurant->store_branch_name;
    //   $file_pointer =  $dirPath . '/build.js';
    //
    //   // Use unlink() function to delete a file
    //   if (!unlink($file_pointer)) {
    //       Yii::error("$file_pointer cannot be deleted due to an error", __METHOD__);
    //   } else {
    //       if (!rmdir($dirPath)) {
    //           Yii::error("Could not remove $dirPath", __METHOD__);
    //       }
    //   }
    // }



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
