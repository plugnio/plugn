<?php
namespace console\controllers;

use Yii;
use common\models\Restaurant;
use yii\helpers\Console;

/**
 * Description of EventController
 *
 * @author krishna
 */
class EventController extends \yii\console\Controller {
    //put your code here
    
    public $event;
    
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['event']);
    }

    public function syncAddonPurchase() {
        // \common\models\SubscriptionPayment
    }
    
    public function syncPlanPurchase() {
        // \common\models\SubscriptionPayment
    }
    
    public function syncStoreCreated() {
        
        $query = Restaurant::find();
            //->joinWith(['agents']);

        $count = 0;

        $total = Restaurant::find()
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $stores) {

            $count += sizeof($stores);

            foreach ($stores as $store) {

                $agent = $store->getAgents()->one();
                
                if(!$agent)
                    continue;
                
                $datetime = new \DateTime($store->restaurant_created_at);

                $full_name = explode(' ', $agent->agent_name);
                $firstname = $full_name[0];
                $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

                Yii::$app->eventManager->setUser($agent->agent_id, [
                    'name' => trim($agent->agent_name),
                    'email' => $agent->agent_email,                    
                ]);
                
                Yii::$app->eventManager->track('Store Created', [
                        'first_name' => trim($firstname),
                        'last_name' => trim($lastname),
                        'store_name' => $store->name,
                        'phone_number' => $store->owner_phone_country_code . $store->owner_number,
                        'email' => $agent->agent_email,
                        'store_url' => $store->restaurant_domain
                    ],
                    $datetime->format('c'),
                    $agent->agent_id
                );                
            }

            Console::updateProgress($count, $total);
        }

        Yii::$app->eventManager->flush();
    }
    
    public function syncOrderCompleted() {
        // \common\models\SubscriptionPayment
    }

    /**
     * sync suggestion with segment
     */
    public function actionEmulate() {

        switch ($this->event) {
            case "Addon Purchase":
                $this->syncAddonPurchase();
                break;
            case "Premium Plan Purchase":
                $this->syncPlanPurchase();
                break;
            case "Store Created":
                $this->syncStoreCreated();
                break;
            case "Order Completed":
                $this->syncOrderCompleted();
                break;
            
            default:
                $this->stdout("Missing event name \n", Console::FG_RED, Console::BOLD);
                //throwException("Missing event name");
        }
    }
}
