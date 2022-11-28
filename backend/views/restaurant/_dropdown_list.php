<ul class="list-group"">
<?php foreach($dataProvider->getModels () as $store) { ?>
    <li class="item list-group-item" data-key="<?= $store->restaurant_uuid ?>"
        data-value="<?= $store->name ?>">
        <h5><?= $store->name ?></h5>
        <small><?= $store->name_ar ?></small>
    </li>

<?php } ?>


</ul>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $dataProvider->getPagination (),
]);  ?>
