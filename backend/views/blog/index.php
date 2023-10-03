<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blog posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php foreach ($posts as $post) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <h3 class="panel-title">
                <?= isset($post['blogPostDescriptions'][0])? $post['blogPostDescriptions'][0]['title']: "Post #". $post["ID"] ?>
            </h3>

            <br />

            <?= Html::a('View', ['blog/view', 'id' => $post['ID']], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php } ?>


</div>
