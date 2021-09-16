<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Admin dashboard';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Plugn Management!</h1>

        <p class="lead">Dashboard with a summary of whats going on in the project</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-12 col-lg-4">
                <h2>Stores</h2>

                  <?= Html::a('Go &raquo', ['restaurant/index'], ['class' => 'btn btn-default']) ?>
            </div>
            <div class="col-12 col-lg-4">
                <h2>Agents</h2>

                  <?= Html::a('Go &raquo', ['agent/index'], ['class' => 'btn btn-default']) ?>
            </div>

            <div class="col-12 col-lg-4">
                <h2>Agent Assignment</h2>

                  <?= Html::a('Go &raquo', ['agent-assignment/index'], ['class' => 'btn btn-default']) ?>
            </div>

        </div>

        <div class="row">
            <div class="col-12 col-lg-4">
                <h2>Partners</h2>

                  <?= Html::a('Go &raquo', ['partner/index'], ['class' => 'btn btn-default']) ?>
            </div>
            <div class="col-12 col-lg-4">
                <h2>Payable Partners</h2>

                  <?= Html::a('Go &raquo', ['partner-payout/index'], ['class' => 'btn btn-default']) ?>
            </div>


        </div>

    </div>
</div>
