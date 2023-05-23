<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VendorCampaign */

$this->title = $model->campaign_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Email Campaigns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);


if ($model->status == \common\models\VendorCampaign::STATUS_IN_PROGRESS) {
    $js = "

        var ele = document.getElementById('campaign-progress-bar');
        var eleStatus = document.getElementById('txt-progress');
        
        setInterval(function() {
        
        $.ajax({
				url: '" . yii\helpers\Url::to(['vendor-campaign/status', 'id' => $model->campaign_uuid]) . "',
				dataType: 'json',
				success: function(json) {
				    ele.style.width = json.progress + '%';
				    eleStatus.innerHTML = json.progress + '%';
				    
				    if(json.progress == 100) {
				        window.location = location;//reload page
				    }
				}
		}); 
        }, 1000);
	
    ";

    $this->registerJs($js);
}

?>
<div class="vendor-campaign-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($model->status == \common\models\VendorCampaign::STATUS_DRAFT) { ?>

        <?= Html::a(Yii::t('app', 'Start Campaign'), ['run', 'id' => $model->campaign_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to send mail to all stores?'),
                'method' => 'post',
            ],
        ]) ?>

            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->campaign_uuid], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->campaign_uuid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>

        <?php } ?>
    </p>

    <?php if($model->status == \common\models\VendorCampaign::STATUS_IN_PROGRESS) { ?>
            <br />
            <h5>
                Progress <span id="txt-progress"><?= $model->progress ?>%</span></h5>
    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
             id="campaign-progress-bar"
             style="width: <?= $model->progress ?>%" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
        <hr />
    <?php } ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'campaign_uuid',
            'template_uuid',
            //'progress',
            'statusName',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
