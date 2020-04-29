

<?php
use yii\helpers\Html;
use common\models\Order;


if($orders){
foreach ($orders as $order) {
    ?>
    <tr>
        <td>
            <?=
            Html::a('#' . $order->order_uuid, ['order/view', 'id' => $order->order_uuid, 'restaurantUuid' => $order->restaurant_uuid],['target' => '_blank'])
            ?>
        </td>
        <td>
            <?= $order->customer_name ?>
        </td>
        <td>

            <?php
            $options = ['class' => ''];

            if ($order->order_status == Order::STATUS_PENDING) {
                Html::addCssClass($options, ['badge badge-warning']);
            } else if ($order->order_status == Order::STATUS_BEING_PREPARED) {
                Html::addCssClass($options, ['badge badge-warning']);
            } else if ($order->order_status == Order::STATUS_OUT_FOR_DELIVERY) {
                Html::addCssClass($options, ['badge badge-primary']);
            } else if ($order->order_status == Order::STATUS_COMPLETE) {
                Html::addCssClass($options, ['badge badge-success']);
            } else if ($order->order_status == Order::STATUS_REFUNDED) {
                Html::addCssClass($options, ['badge badge-info']);
            } else if ($order->order_status == Order::STATUS_CANCELED) {
                Html::addCssClass($options, ['badge badge-danger']);
            }

            echo Html::tag('span', $order->orderStatus, $options);
            ?>
        </td>
        <td>
            <div class="sparkbar" data-color="#00a65a" data-height="20">
                <?= Yii::$app->formatter->asRelativeTime($order->order_created_at); ?>
            </div>
        </td>
    </tr>
<?php } } else {?>
<td>No results found.</td>
<?php } ?>
    
