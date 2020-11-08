<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;
$this->title = 'Domains';
?>

<section>
    <div class="card">

        <div class="card-header">
            <h5>Primary domain</h5>
            <?php

              if($restaurant_model->has_deployed)
                echo Html::a('Update', ['connect-domain', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-primary'])

             ?>

        </div>

        <div class="card">
            <div class="card-body">
                <div class="box-body table-responsive no-padding">

                    <table id="w0" class="table table-hover text-nowrap table-bordered">
                        <tbody>
                            <tr>
                                <th>Domain name</th>
                                <td><?= $restaurant_model->restaurant_domain ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </div>


</section>
