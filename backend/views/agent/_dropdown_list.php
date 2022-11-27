<ul class="list-group"">
<?php foreach($dataProvider->getModels () as $agent) { ?>
    <li class="item list-group-item" data-key="<?= $agent->agent_id ?>"
        data-value="<?= $agent->agent_name ?>">
        <?= $agent->agent_name ?>
        <small><?= $agent->agent_email ?></small>
    </li>

<?php } ?>


</ul>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $dataProvider->getPagination (),
]);  ?>
