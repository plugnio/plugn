<?php

use backend\models\Admin;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Staffs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add Staff', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'staff_name',
            'staff_email:email',
            // 'admin_auth_key',
            // 'admin_password_hash',
            //'admin_password_reset_token',
            // 'admin_status',
            //'admin_created_at',
            //'admin_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}'],
        ],
    ]); ?>
</div>
