

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

          <?php
          $options = ['class' => ''];

          if ($order->order_status == Order::STATUS_PENDING) {
              Html::addCssClass($options, ['fa fa-circle font-small-3 text-warning mr-50']);
          } elseif ($order->order_status == Order::STATUS_BEING_PREPARED) {
              Html::addCssClass($options, ['fa fa-circle font-small-3 text-primary mr-50']);
          } elseif ($order->order_status == Order::STATUS_OUT_FOR_DELIVERY) {
              Html::addCssClass($options, ['fa fa-circle font-small-3 text-info mr-50']);
          } elseif ($order->order_status == Order::STATUS_COMPLETE) {
              Html::addCssClass($options, ['fa fa-circle font-small-3 success-info mr-50']);
          } elseif ($order->order_status == Order::STATUS_REFUNDED) {
              Html::addCssClass($options, ['fa fa-circle font-small-3 text-danger mr-50']);
          } elseif ($order->order_status == Order::STATUS_CANCELED) {
              Html::addCssClass($options, ['fa fa-circle font-small-3 text-danger mr-50']);
          }

          echo Html::tag('i', '', $options) . $order->orderStatus
          ?>
        </td>
        <td>
      <?= $order->customer_name ?>
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
