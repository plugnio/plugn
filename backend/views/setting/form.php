<?php

use yii\helpers\Html;

/* @var $settings [] */

$this->title = 'Plugn Settings';
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="admin-form">

        <?= Html::beginForm(['setting/update'], 'POST'); ?>

        <div class="panel panel-default">
            <div class="panel-heading">Segment</div>
            <div class="panel-body">

                <div class="form-group">
                <?= Html::checkbox('Segment-Status', !is_null($settings['Segment-Status']), [
                        "label" => "Segment Status"
                ]); ?>
                </div>

                <div class="form-group">
                    <?= Html::label('Segment Key', 'Segment-Key', ['class' => 'control-label']) ?>
                    <?= Html::textInput('Segment-Key', $settings['Segment-Key'], ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                <?= Html::label('Segment Key for Wallet', 'Segment-Key-Wallet', ['class' => 'control-label']) ?>
                <?= Html::textInput('Segment-Key-Wallet', $settings['Segment-Key-Wallet'], ['class' => 'form-control']); ?>
                </div>

            </div>
        </div>


        <div class="panel panel-default">

            <div class="panel-heading">Mixpanel</div>
            <div class="panel-body">

                <div class="form-group">
                <?= Html::checkbox('Mixpanel-Status', !is_null($settings['Mixpanel-Status']), [
                    "label" => "Mixpanel Status"
                ]); ?>
                </div>

                <div class="form-group">
                <?= Html::label('Mixpanel Key', 'Mixpanel-Key', ['class' => 'control-label']) ?>
                <?= Html::textInput('Mixpanel-Key', $settings['Mixpanel-Key'], ['class' => 'form-control']); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?= Html::endForm(); ?>

    </div>


</div>
