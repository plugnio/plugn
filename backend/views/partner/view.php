<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Partner */

$this->title = $model->partner_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="partner-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'partner_uuid',
            'username',
            'partner_auth_key',
            'partner_password_hash',
            'partner_password_reset_token',
            'partner_email:email',
            'partner_status',
            'referral_code',
            'commission',
            'partner_created_at',
            'partner_updated_at',
        ],
    ]) ?>

</div>
