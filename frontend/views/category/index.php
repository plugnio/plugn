<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?= Html::encode($this->title) ?></h3>
    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
<div class="card">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summaryOptions' => ['class' => "card-header"],
        'columns' => [
            'sort_number',
            'category_name',
            'category_name_ar',
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
    ]);
    ?>

</div>
