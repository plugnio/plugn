<?php
use yii\helpers\Html;
use backend\components\ChartWidget;

$this->title = 'Plugn COD commission report';

?>

<div class="site-index">
    <div class="body-content">

        <div class="grid">

            <h3>Plugn COD commission report</h3>

            <br />

            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="title">Download COD commission report</span>
                </div>

                <div class="panel-body">

                    <?= Html::beginForm(['/report/download-cash-on-delivery'], 'GET', ['class' => "form form-filter"]); ?>

                    <div class="form-group mb-2">
                        <label for="date_start">Start Date</label>
                        <?= Html::input('date', 'date_start', null, ["id" => "date_start", "class"=>"form-control"]); ?>
                    </div>

                    <div class="form-group mb-2">
                        <label for="end_start">End Date</label>
                        <?= Html::input('date', 'date_end', null, ["id" => "date_end", "class"=>"form-control"]); ?>
                    </div>

                    <div class="form-group mb-2">
                        <label for="end_start">Restaurant UUID</label>
                        <?= Html::input('text', 'restaurant_uuid', null, ["id" => "restaurant_uuid", "class"=>"form-control"]); ?>
                    </div>

                    <div class="form-group mb-2">
                        <label for="end_start">Country</label>
                        <?= Html::dropdownList('country_id', null, $countries, ["id" => "country_id", "class"=>"form-control"]); ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 0px; padding-bottom: 0px;">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary mb-2']) ?>
                    </div>

                    <?= Html::endForm(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
