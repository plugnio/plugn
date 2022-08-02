<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Customer: ' . $model->customer_name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-create">

      <?= $this->render('_form', [
          'model' => $model,
      ]) ?>

  </div>
