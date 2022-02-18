<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Real Time Orders';
$this->params['breadcrumbs'][] = $this->title;
$js = "
$(function () {
  $('.summary').insertAfter('.top');
  
  $('input[name=\"OrderSearch[order_created_at]\"]').pickadate({
    format: 'd mmmm yyyy',
  });

});

";
$this->registerJs($js);
?>
<script type = "text/javascript">

var soundForNewOrders = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8avaIf5SvL7pndPvPpndJR9Kuu8fePvuiuhorgWjp7Mf/PRjxcFCPDkW31srioCExivv9lcwKEaHsf/7ow2Fl1T/9RkXgEhYElAoCLFtMArxwivDJJ+bR1HTKJdlEoTELCIqgEwVGSQ+hIm0NbK8WXcTEI0UPoa2NbG4y2K00JEWbZavJXkYaqo9CRHS55FcZTjKEk3NKoCYUnSQ0rWxrZbFKbKIhOKPZe1cJKzZSaQrIyULHDZmV5K4xySsDRKWOruanGtjLJXFEmwaIbDLX0hIPBUQPVFVkQkDoUNfSoDgQGKPekoxeGzA4DUvnn4bxzcZrtJyipKfPNy5w+9lnXwgqsiyHNeSVpemw4bWb9psYeq//uQZBoABQt4yMVxYAIAAAkQoAAAHvYpL5m6AAgAACXDAAAAD59jblTirQe9upFsmZbpMudy7Lz1X1DYsxOOSWpfPqNX2WqktK0DMvuGwlbNj44TleLPQ+Gsfb+GOWOKJoIrWb3cIMeeON6lz2umTqMXV8Mj30yWPpjoSa9ujK8SyeJP5y5mOW1D6hvLepeveEAEDo0mgCRClOEgANv3B9a6fikgUSu/DmAMATrGx7nng5p5iimPNZsfQLYB2sDLIkzRKZOHGAaUyDcpFBSLG9MCQALgAIgQs2YunOszLSAyQYPVC2YdGGeHD2dTdJk1pAHGAWDjnkcLKFymS3RQZTInzySoBwMG0QueC3gMsCEYxUqlrcxK6k1LQQcsmyYeQPdC2YfuGPASCBkcVMQQqpVJshui1tkXQJQV0OXGAZMXSOEEBRirXbVRQW7ugq7IM7rPWSZyDlM3IuNEkxzCOJ0ny2ThNkyRai1b6ev//3dzNGzNb//4uAvHT5sURcZCFcuKLhOFs8mLAAEAt4UWAAIABAAAAAB4qbHo0tIjVkUU//uQZAwABfSFz3ZqQAAAAAngwAAAE1HjMp2qAAAAACZDgAAAD5UkTE1UgZEUExqYynN1qZvqIOREEFmBcJQkwdxiFtw0qEOkGYfRDifBui9MQg4QAHAqWtAWHoCxu1Yf4VfWLPIM2mHDFsbQEVGwyqQoQcwnfHeIkNt9YnkiaS1oizycqJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");

   function enableSoundForNewOrders() {
    document.getElementById("play-btn").value = 'true';
    document.getElementById("stop-btn").value = 'false'

    document.getElementById("play-sound-section").style.display = "none";
    document.getElementById("stop-sound-section").style.display = "block";
   }


   function disableSoundForNewOrders() {
    document.getElementById("stop-btn").value = 'true';
    document.getElementById("play-btn").value = 'false'
    document.getElementById("play-sound-section").style.display = "block";
    document.getElementById("stop-sound-section").style.display = "none";
   }


   async function CheckPendingOrders() {

       let url = <?= "'" . Yii::$app->params['apiEndpoint'] . '/v1/order/check-for-pending-orders/' . $storeUuid . "'" ?>;
       
       /*const inptOrderUUID = $("input[name='OrderSearch[order_uuid]']");
       const inptOrderCreatedAt = $("input[name='OrderSearch[order_created_at]']");
       const inptBusiness = $("input[name='OrderSearch[business_location_name]']");
       const inptCustomer = $("input[name='OrderSearch[customer_name]']");
       const inptPhone = $("input[name='OrderSearch[customer_phone_number]']");
       const inptPayment = $("input[name='OrderSearch[payment_method_name]']");
       const inptPrice = $("input[name='OrderSearch[total_price]']");*/
       

       fetch(url, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
          // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        //body: JSON.stringify(data)
       })
               .then(res => res.json())
               .then(isNewOrder => {

                    if(isNewOrder) {

                       let formData = {};

                       $('#new-order-table input').each(function() {
                       
                            var name = $(this).attr("name");
                            var val = $(this).val();//attr("value");

                            formData[name] = val;        
                       });

                        $("#new-order-table tbody").load(<?= "'" . yii\helpers\Url::to(['site/check-for-new-orders', 'storeUuid' => $storeUuid, 'page' => $page]) . " tbody tr'" ?>, formData);
                    }

                   if (isNewOrder && document.getElementById("play-btn").value == 'true' && document.getElementById("stop-btn").value == 'false') {
                     // console.log('play');
                     soundForNewOrders.play();
                   } else if (!isNewOrder && document.getElementById("stop-btn").value == 'true' && document.getElementById("play-btn").value == 'false') {
                     // console.log('pause');
                     soundForNewOrders.pause();
                   }

               }).catch(err => {
       console.error('Error: ', err);
       });
     }

     setInterval(function () {
       CheckPendingOrders();
     }, 2500);

    window.onload = (event) => {
      CheckPendingOrders();
    };

</script>

<section id="data-list-view" class="data-list-view-header">


    <div class="btn-group dropdown actions-dropodown" style="box-shadow: none;">
        <div id="play-sound-section">
            <?=
            Html::button(' <i class="fa fa-play" style="margin-right: 10px;"></i> Play sound for new orders', ['class' => 'btn btn-success mr-1 mb-1 waves-effect waves-light', 'id' => 'play-btn', 'value' => 'false', 'onclick' => 'enableSoundForNewOrders()']);
            ?>
        </div>
        <div id="stop-sound-section" style="display: none">
            <?=
            Html::button('<i class="fa fa-stop" style="margin-right: 10px;"></i> Stop sound for new orders', ['class' => 'btn btn-danger mr-1 mb-1 waves-effect waves-light', 'id' => 'stop-btn', 'value' => 'false', 'onclick' => 'disableSoundForNewOrders()']);
            ?>
        </div>
    </div>

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function($model) {
                $url = Url::to(['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Order ID',
                    'attribute' => 'order_uuid',
                    "format" => "raw",
                    "value" => function($model) {
                        return Html::a('#' . $model->order_uuid, ['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);
                    }
                ],
                [
                    'attribute' => 'order_created_at',
                    "format" => "raw",
                    "value" => function($model) {
                        return date('d M - h:i A', strtotime($model->order_created_at));
                    }
                ],

                [
                    'attribute' => 'business_location_name'
                ],

                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->customer_id)
                            return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'storeUuid' => $data->restaurant_uuid]);
                    },
                    'visible' => function ($data) {
                        return $data->customer_id ? true : false;
                    },
                ],
                [
                    'attribute' => 'customer_phone_number',
                    "format" => "raw",
                    "value" => function($model) {
                      return '<a href="tel:'. $model->customer_phone_number .'"> '. str_replace(' ', '', $model->customer_phone_number) .' </a>';
                    }
                ],
                [
                    'attribute' => 'payment_method_name',
                    'label' => 'Payment',
                    "format" => "raw",
                    "value" => function($data) {
                        return $data->paymentMethod->payment_method_name;
                    },
                    "visible" => function($data) {
                        return $data->payment->payment_current_status;
                    },
                ],
                [
                    'attribute' => 'total_price',
                    "value" => function($data) {
                            return Yii::$app->formatter->asCurrency($data->total_price, $data->currency->code, [
                                \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                            ]);
                    },
                ],
            ],
            'layout' => '{items}{pager}',
            'tableOptions' => ['class' => 'table dataTable data-list-view', 'id' => 'new-order-table'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

</section>
<!-- Data list view end -->
