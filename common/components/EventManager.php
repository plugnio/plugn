<?php 
namespace common\components;

use agent\models\AgentAssignment;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class EventManager extends Component
{
    private $_client;

    /**
     * @var string Mixpanel key
     */
    public $key;

    /**
     * @var string | null Mixpanel status
     */
    public $mixpanelStatus;

    /**
     * @var string | null Segment status
     */
    public $segmentStatus;

    /**
     * @var string | null Segment key
     */
    public $segmentKey;

    /**
     * @var string | null Segment key for wallet app
     */
    public $walletSegmentKey;

    /**
     * @var boolean Whether segment identity defined
     */
    private $segmentIdentify;

     /**
     * @inheritdoc
     */
    public function init()
    {
        /*if ($this->key === null) {
            throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                '{class}' => static::className(),
                '{attribute}' => '$key'
            ]));
        }*/

        parent::init();

        //'key' => 'bfe2ac5e039a3d8d1c8e281967d6f954',//test: ac62dbe81767f8871f754c7bdf6669d6
        //'segmentKey' => 'WZc7uvfkM1uhsjT1Eie6PONXFZK3ME15'//test: 7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh
        //'walletSegmentKey' => 'j18MpMF6fvZzmc6bvF0VjlTajAlKwai2'//test: 7oEpdGxjwBMlwBQYuXD7NpYWp4HzDJWh

        $this->segmentStatus = Yii::$app->config->get('Segment-Status');
        $this->mixpanelStatus = Yii::$app->config->get('Mixpanel-Status');

        if($this->mixpanelStatus) {

            if (YII_ENV == 'prod') {
                $this->key = Yii::$app->config->get('Mixpanel-Key');
            } else {
                $this->key = Yii::$app->config->get('Test-Mixpanel-Key');
            }

            $this->_client = \Mixpanel::getInstance($this->key);
        }

        if($this->segmentStatus) {

            if (YII_ENV == 'prod') {
                $this->segmentKey = Yii::$app->config->get('Segment-Key');
                $this->walletSegmentKey = Yii::$app->config->get('Segment-Key-Wallet');
            } else {
                $this->segmentKey = Yii::$app->config->get('Test-Segment-Key');
                $this->walletSegmentKey = Yii::$app->config->get('Test-Segment-Key-Wallet');
            }

            if($this->segmentKey)
                \Segment::init($this->segmentKey);
        }
    }

    public function initSegment($key) {

        $this->segmentKey = $key;

        \Segment::init($key);
    }

    /**
     * set user for trackinng/event management
     */     
    public function setUser($id, $data) 
    {
        try {
            $ip = Yii::$app->getRequest()->getUserIP();
        } catch (yii\base\UnknownMethodException $exception) {
            $ip = "192.168.0.1";
        }

        if($this->_client) {
            $this->_client->identify($id);
            $this->_client->people->set($id, $data, $ip, $ignore_time = false);
        }

        if($this->segmentKey) {
            \Segment::identify([
                "userId" => $id,
                "traits" => $data
            ]);

            $this->segmentIdentify = true;
        }
    }

    /**
     * todo: userId not passing in mixpanel?
     * register event 
     */
    public function track($event, $eventData, $timestamp = null, $storeId = null)
    {
        if(!$storeId)
            $storeId = Yii::$app->request->headers->get('Store-Id');

        $language = Yii::$app->language == "ar"? "ar": "en";

        $eventData = array_merge($eventData, [
            "company_id" => $storeId,
            "store_id" => $storeId,
            "language" => $language,
            "do_not_disturb" => null,
        ]);

        //if login

        if(!Yii::$app->user->isGuest) {

            //if agent login

            if(isset(Yii::$app->user->identity->agent_name)) {

                $assignment = AgentAssignment::find()
                    ->andWhere(['restaurant_uuid' => $storeId, "agent_id" => Yii::$app->user->getId()])
                    ->one();

                if ($assignment)
                    $eventData["role"] = $assignment->role;

                $eventData["channel"] = "Dashboard Web App";
            }
            //if customer login
            else if(isset(Yii::$app->user->identity->customer_id))
            {
                $eventData["channel"] = "Store Web App";
            }
        }

        if(empty($eventData["channel"])) {
            $eventData["channel"] = "Backend";
        }

        //if login and userId not provided

        /*if(is_null($userId) && isset(Yii::$app->user) && !Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->getId();
        }*/

        if (empty(Yii::$app->user) || Yii::$app->user->isGuest) {
            $userId = $storeId;
        } else {
            $userId = Yii::$app->user->getId();

            /*if($this->_client) {
                $this->_client->identify($userId);
                $this->_client->people->set($userId, [
                    'name' => trim(Yii::$app->user->identity->agent_name),
                    'email' => Yii::$app->user->identity->agent_email,
                ]);
            }*/
        }

        if($this->_client) {
            
            $mixpanelData = $eventData;
            
            if($timestamp) {
                $mixpanelData =  array_merge([
                    "\$time" => strtotime($timestamp),
                    "\$created" => $timestamp,
                ], $eventData);
            }

            if($userId) {
                $mixpanelData['$distinct_id'] = $userId;
                $mixpanelData['$user_id'] = $userId;
            }
            $this->_client->track($event, $mixpanelData);

            //to fix order
            $this->_client->flush();
        }
        
        if($this->segmentKey) {

            $data = [
                'event' => $event,
                'properties' => $eventData,
                'timestamp' => $timestamp
            ];

            if(!$userId) {
                $userId = "anonymous";
            }

            if($this->segmentIdentify)  {
                $data['userId'] = $userId;
            } else {
                $data['anonymousId'] = $userId;
            }

            \Segment::track($data);

            \Segment::flush();
        }
    }

    public function flush()
    {
        if($this->segmentKey)
            \Segment::flush();
    }
}