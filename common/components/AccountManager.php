<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use common\models\Restaurant;

/**
 * AccountManager is a component that holds a list of Restaurants this agent owns
 * The purpose of this component is to reduce the stress incurred on the database
 * Example Usage:
 * - Get list of restaurants this agent owns
 * - Check if agent is authorised to make actions on behalf of an account
 */
class AccountManager  extends BaseObject
{
    /**
     * Restaurants this agent owns
     * @var \common\models\Restaurant
     */
    private $_managedAccounts = false;

    /**
     * Sets up the AccountManager component for use to manage restaurants
     *
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        // This component must only be usable if agent is logged in
        if(Yii::$app->user->isGuest){
            throw new \yii\web\BadRequestHttpException('ILLEGAL USAGE OF ACCOUNT OWNERSHIP MANAGER');
        }

          // Getting a list of Restaurants this agent manages
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT '.Yii::$app->user->identity->agent_id.', COUNT(*) FROM agent WHERE agent_id='.Yii::$app->user->identity->agent_id,


            // we SELECT agent_id as well to make sure every cached sql statement is unique to this agent
            // don't want agents viewing the cached content of another agent
            // SUM of agent_status is to bust the cache when status changes
        ]);
//
        $cacheDuration = 60*15; //15 minutes then delete from cache

        $this->_managedAccounts = Restaurant::getDb()->cache(function($db) {
            return Yii::$app->user->identity->getAccountsManaged()->all();
        }, $cacheDuration, $cacheDependency);


         // Getting a list of Restaurants this agent manages
        // No cache
        // $this->_managedAccounts = Yii::$app->user->identity->getAccountsManaged()->all();

        parent::__construct($config);
    }

    /**
     * Returns the restaurants managed by this agent
     *
     * @return \common\models\Restaurant    Records of Restaurants managed by this agent
     */
    public function getManagedAccounts(){
        return $this->_managedAccounts;
    }

    /**
     * Gets a single restaurant that the agent owns based on restaurantUuid
     *
     * @param integer $restaurantUuid id number of the restaurant
     * @return \common\models\Restaurant  The user account
     * @throws \yii\web\NotFoundHttpException if the account isnt one this agent owns
     */
    public function getManagedAccount($restaurantUuid){
        foreach($this->_managedAccounts as $restaurant){
            if($restaurant->restaurant_uuid == $restaurantUuid){
                 return $restaurant;
            }
        }

        Yii::$app->user->logout();
        throw new \yii\web\BadRequestHttpException('You do not own this store.');
    }

}
