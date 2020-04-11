<?php

namespace console\controllers;

use common\models\Email;
use Yii;
use yii\helpers\Console;
use common\models\Payment;
use common\models\Currency;
use common\models\Employer;
use common\models\CandidateJobApplication;
use common\models\CandidateJobApplicationTrack;
use common\models\Job;
use common\models\Candidate;
use common\models\Agent;


/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

    /**
     * Used for testing only
     */
    public function actionIndex() {
        $restaurant_model = \common\models\Restaurant::findOne('rest_dda9e02f-74c6-11ea-9c22-063f7dee6a14');
        $restaurant_theme = new \common\models\RestaurantTheme;
        $restaurant_theme->restaurant_uuid = $restaurant_model->restaurant_uuid;
        $restaurant_theme->save();
    }

}
