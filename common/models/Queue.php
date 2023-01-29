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
 * @property string|null $queue_response
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
    const QUEUE_STATUS_HOLD = 4; // IN CASE OF multiple account to confirm from client
    const QUEUE_STATUS_FAILED = 5;

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
            [['queue_start_at', 'queue_end_at','queue_response'], 'safe'],
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

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) {

        if (!$insert && $this->queue_status == self::QUEUE_STATUS_CREATING) {

            $store_model = $this->restaurant;

            $getLastCommitResponse = Yii::$app->githubComponent->getLastCommit();

            if($getLastCommitResponse->headers['http-code'] == 200) { // success

                $sha = $getLastCommitResponse->data['sha'];

                if (!$sha) {
                    $this->queue_response = 'Invalid SSH';
                    $this->addError('restaurant_uuid', 'Invalid SSH');
                    //Yii::error('Invalid SSH key while fetching gihub result', __METHOD__);
                    return false;
                }
                
                //Replace test with store branch name
                $branchName = 'refs/heads/' . $store_model->store_branch_name;

                $createBranchResponse = Yii::$app->githubComponent->createBranch($sha, $branchName);

                /**
                 * if branch already exists
                 *
                if(Yii::$app->githubComponent->isBranchExists($store_model->store_branch_name)) {

                }*/ 

                if($createBranchResponse->headers['http-code'] == 201) { // Created

                    sleep(1);

                    $url = parse_url($store_model->restaurant_domain);

                    $createNewSiteResponse = Yii::$app->netlifyComponent->createSite($url['host'], $store_model->store_branch_name);

                        if ($createNewSiteResponse->isOk) {

                            $site_id = $createNewSiteResponse->data['site_id'];
                            $store_model->site_id = $site_id;
                            $store_model->save(false);
                            $this->queue_response = json_encode($createNewSiteResponse->data);
                            $this->queue_status = Queue::QUEUE_STATUS_COMPLETE;
                            return true;

                        } else {

                            $this->queue_response = json_encode($createNewSiteResponse);

                            if (isset(Yii::$app->session->id)) {
                                Yii::$app->session->setFlash('error', '[Netlify > While Creating new site]' . json_encode($createNewSiteResponse->data));
                            }

                            $this->addError('restaurant_uuid', '[Netlify > While Creating new site]' . json_encode($createNewSiteResponse->data));

                            //Yii::error('[Netlify > While Creating new site]' . json_encode($createNewSiteResponse->data), __METHOD__);

                            return false;
                        }

                } else{
                    $this->queue_response = json_encode($createBranchResponse);
                    if (isset(Yii::$app->session->id)) {
                        Yii::$app->session->setFlash('error', '[Github > Create branch]' . json_encode($createBranchResponse->data['message']) . ' RestaurantUuid: ' . $store_model->restaurant_uuid);
                    }

                    $this->addError('restaurant_uuid', '[Github > Create branch]' . json_encode($createBranchResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid. ' Named: '. $store_model->name);

                    //Yii::error('[Github > Create branch]' . json_encode($createBranchResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid. ' Named: '. $store_model->name, __METHOD__);

                    return false;
                }

            } else {
                $this->queue_response = json_encode($getLastCommitResponse);

                if (isset(Yii::$app->session->id)) {
                    Yii::$app->session->setFlash('error', '[Github > Last commit]' . json_encode($getLastCommitResponse->data['message']) . ' RestaurantUuid: ' . $store_model->restaurant_uuid);
                }

                $this->addError('restaurant_uuid', '[Github > Last commit]' . json_encode($getLastCommitResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid. ' Named: '. $store_model->name);

                //Yii::error('[Github > Last commit]' . json_encode($getLastCommitResponse->data['message']) . ' RestaurantUuid: '. $store_model->restaurant_uuid. ' Named: '. $store_model->name, __METHOD__);

                return false;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'queue_id' => Yii::t('app','Queue ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'queue_status' => Yii::t('app','Queue Status'),
            'queue_response' => Yii::t('app','Queue response'),
            'queue_created_at' => Yii::t('app','Queue Created At'),
            'queue_updated_at' => Yii::t('app','Queue Updated At'),
            'queue_start_at' => Yii::t('app','Queue Start At'),
            'queue_end_at' => Yii::t('app','Queue End At')
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
