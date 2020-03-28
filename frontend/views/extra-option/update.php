    <?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Update Extra Option: ' . $model->extra_option_name;
$this->params['breadcrumbs'][] = ['label' => $model->item->item_name, 'url' => ['item/view', 'id' => $model->item->item_uuid ,'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->option->option_name, 'url' => ['option/view' ,'id' => $model->option->option_id, 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->extra_option_name, 'url' => ['view', 'id' => $model->extra_option_id, 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="extra-option-update">


    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
