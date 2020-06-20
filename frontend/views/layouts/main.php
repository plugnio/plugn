
<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\DashboardAsset;
use common\widgets\Alert;
use common\models\Restaurant;
use common\models\AgentAssignment;

DashboardAsset::register($this);

$restaurant_model = Restaurant::findOne($this->params['restaurant_uuid']);
?>
