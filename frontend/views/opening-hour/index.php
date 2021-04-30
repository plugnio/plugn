<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\OpeningHour;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opening Hours';
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $storeUuid;


$this->registerCss("
.custom-switch.switch-lg .custom-control-label::before , .custom-control-input:checked ~ .custom-control-label::before{
      background-color: #28C76F !important;
  }

  .custom-switch.switch-lg .custom-control-label .switch-text-right, .custom-switch.switch-lg .custom-control-label .switch-icon-right {
    color:white !important;
  }


.custom-switch-success .custom-control-input:checked ~ .custom-control-label::before {
      background-color: #EA5455 !important;
  }



  .qGcNVe {
    list-style: none;
    padding-left: 0;
}

.sM9Z9c {
    align-items: center;
    display: flex;
}
.lXfzDe {
    font-family: Roboto,Arial,sans-serif;
    font-size: 17px;
    font-weight: 400;
    letter-spacing: .02rem;
    line-height: 1.25rem;
    color: #3c4043;
    flex: auto;
    padding-left: 12px;
}
.lXfzDe, .VPQpCe {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.VPQpCe {
    text-align: right;
    font-family: Roboto,Arial,sans-serif;
    font-size: 14px;

    font-weight: 400;
    letter-spacing: .02rem;
    line-height: 1.25rem;
    color: #3c4043;
    flex: auto;
    justify-content: flex-end;
}

.lXfzDe, .VPQpCe {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.eF1gIe, .eF1gIe .KHxj8b {
    color: #70757a;
    text-decoration: line-through;
}
.FzVg0e {
    display: block;
}
.FsnbQ {
    display: flex;
    justify-content: flex-end;
}
.yHy1rc {
    z-index: 0;
}
.VfPpkd-Bz112c-LgbsSe {
    --mdc-ripple-fg-size: 0;
    --mdc-ripple-left: 0;
    --mdc-ripple-top: 0;
    --mdc-ripple-fg-scale: 1;
    --mdc-ripple-fg-translate-end: 0;
    --mdc-ripple-fg-translate-start: 0;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
    will-change: transform,opacity;
    bottom: 10px;
}

.VfPpkd-Bz112c-LgbsSe {
    display: inline-block;
    position: relative;
    box-sizing: border-box;
    border: none;
    outline: none;
    background-color: transparent;
    fill: currentColor;
    color: inherit;
    font-size: 24px;
    text-decoration: none;
    cursor: pointer;
    -webkit-user-select: none;
    width: 48px;
    height: 48px;
    padding: 12px;
}



.UxKNpc {
    color: #70757a;
}

.kydvwe {
    align-items: center;
    display: flex;
    justify-content: flex-end;
    padding-right: 12px;
}

.VfPpkd-dgl2Hf-ppHlrf-sM5MNb {
    display: inline;
}
.kydvwe .VfPpkd-LgbsSe {
    border-radius: 20px;
    display: block;
    height: 40px;
    margin: auto;
    padding: 0 16px;
}


.nCP5yc:not {
    background-color: #1a73e8;
    background-color: var(--gm-fillbutton-container-color,#1a73e8);
    color: #fff;
    color: var(--gm-fillbutton-ink-color,#fff);
}
.VfPpkd-LgbsSe-OWXEXe-k8QpJ:not {
    color: #fff;
    color: var(--mdc-theme-on-primary,#fff);
}

.VfPpkd-LgbsSe:not, .VfPpkd-LgbsSe:disabled {
    background-color: transparent;
}

.VfPpkd-LgbsSe {
  background: #1a73e8 !important;
  border-color: transparent !important;
  color: white !important;
}

.nCP5yc {
    font-family: 'Google Sans',Roboto,Arial,sans-serif;
    font-size: .875rem;
    letter-spacing: .0107142857em;
    font-weight: 500;
    text-transform: none;
    transition: border 280ms cubic-bezier(0.4,0,0.2,1),box-shadow 280ms cubic-bezier(0.4,0,0.2,1);
    box-shadow: none;
}

.VfPpkd-LgbsSe-OWXEXe-k8QpJ {
    padding: 0 16px 0 16px;
    height: 36px;
    border-radius: 4px;
    border-radius: var(--mdc-shape-small,4px);
}

.VfPpkd-LgbsSe {
    height: 36px;
    border-radius: 4px;
    border-radius: var(--mdc-shape-small,4px);
    padding: 0 8px 0 8px;
}

.nCP5yc {
    background-color: #1a73e8;
    background-color: var(--gm-fillbutton-container-color,#1a73e8);
    color: #fff;
    color: var(--gm-fillbutton-ink-color,#fff);
}

.nCP5yc .VfPpkd-vQzf8d, .nCP5yc .VfPpkd-kBDsod {
    position: relative;
}

.VfPpkd-LgbsSe-OWXEXe-MV7yeb .VfPpkd-kBDsod, .VfPpkd-LgbsSe-OWXEXe-k8QpJ .VfPpkd-kBDsod, .VfPpkd-LgbsSe-OWXEXe-INsAgc .VfPpkd-kBDsod {
    margin-left: -4px;
    margin-right: 8px;
}
.VfPpkd-LgbsSe .VfPpkd-kBDsod {
    margin-left: 0;
    margin-right: 8px;
    display: inline-block;
    font-size: 1.125rem;
    height: 1.125rem;
    vertical-align: top;
    width: 1.125rem;
}

@media only screen and (min-width:750px) {
  .card{
    width:70%;
  }
}
  ");
?>



<section id="data-list-view" class="data-list-view-header">


    <!-- DataTable starts -->
    <div class="card table-responsive" >

        <div class="card-body" >

            <ul>
                <!-- <tbody> -->
                  <!-- <tr>


                        <td style="padding: 5px  15px">
                    <?php
                    // $form->field($daily_hours, "open_24_hrs", [
                    //   'template' => "<span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Open 24 hours</span><div class='custom-control custom-switch custom-control-inline'>{input}<label class='custom-control-label' for='open24Hrs'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
                    // ])->checkbox([
                    //     'id' => 'open24Hrs',
                    //     'class' => 'custom-control-input'
                    //         ], false)->label(false)
                    ?>
                        </td>
                        <td style="padding: 5px  15px">
                            Set Daily
                        </td>
                        <td style="padding: 5px  15px">
                    <?php
                    // $form->field($daily_hours, "open_at" )->textInput(['class' => 'form-control pickatime-open-at','id'=>'dailyOpenTime', 'style'=>'position: initial;','value'=>'00:00'])->label('Opens at');
                    ?>
                        </td>
                        <td style="padding: 5px  15px" >
                    <?php
                    // $form->field($daily_hours, "close_at")->textInput(['class' => 'form-control pickatime-close-at', 'id'=>'dailyCloseTime','style'=>'position: initial;','value'=>'00:00'])->label('Closes at');
                    ?>
                        </td>

                      </tr> -->




            </ul>

            <div jsname="IVrI7b">
               <ul class="qGcNVe">


                                     <?php for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) { ?>
                                       <li data-attribute-type="8" data-day-index="0" class="sM9Z9c">


                                                 <?php
                                                 switch ($dayOfWeek) {
                                                     case OpeningHour::DAY_OF_WEEK_SATURDAY:
                                                         echo '<span class="lXfzDe">Saturday</span>';
                                                         break;
                                                     case OpeningHour::DAY_OF_WEEK_SUNDAY:
                                                         echo '<span class="lXfzDe">Sunday</span>';
                                                         break;
                                                     case OpeningHour::DAY_OF_WEEK_MONDAY:
                                                         echo '<span class="lXfzDe">Monday</span>';
                                                         break;
                                                     case OpeningHour::DAY_OF_WEEK_TUESDAY:
                                                         echo '<span class="lXfzDe">Tuesday</span>';
                                                         break;
                                                     case OpeningHour::DAY_OF_WEEK_WEDNESDAY:
                                                         echo '<span class="lXfzDe">Wednesday</span>';
                                                         break;
                                                     case OpeningHour::DAY_OF_WEEK_THURSDAY:
                                                         echo '<span class="lXfzDe">Thursday</span>';
                                                         break;
                                                     case OpeningHour::DAY_OF_WEEK_FRIDAY:
                                                         echo '<span class="lXfzDe">Friday</span>';
                                                         break;
                                                 }

                                                  $isClosed = array_search($dayOfWeek, array_column($models, 'day_of_week'));

                                                 ?>
                                                 <div class="VPQpCe">

                                                   <div aria-label="Incorrect hours: Closed" class=<?= !$isClosed && gettype( $isClosed) == "boolean" ? "VPQpCe" : "eF1gIe" ?>>
                                                       Closed
                                                   </div>

                                                    <div class="FzVg0e">

                                                       <?php
                                                         foreach ($models as $index => $model) {

                                                             if ($model->day_of_week == $dayOfWeek) {
                                                                 ?>

                                                                     <div>
                                                                         <?= date('h:i A', strtotime($model->open_at)) ?> -  <?= date('h:i A', strtotime($model->close_at)) ?>
                                                                     </div>

                                                         <?php
                                                       }
                                                     }
                                                        ?>


                                                    </div>
                                                 </div>

                                               <span class="FsnbQ" jsaction="JIbuQc:JAwLEe" data-day-index="0">


                                                  <?=
                                                    Html::a(
                                                            '<span><svg height="24" width="24" class="ME7jKf UxKNpc" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M14.916 3.586a2 2 0 012.828 0l2.672 2.671a2 2 0 010 2.829l-.817.816-.013.014L8.5 21.002H3v-5.5l9.33-9.33 2.586-2.586zm-1.172 4L5 16.33v2.672h2.672l8.744-8.745-2.672-2.671z"></path>
                                                            </svg></span>',
                                                              ['update', 'storeUuid' => $storeUuid, 'dayOfWeek' => $dayOfWeek],
                                                               ['class' => 'VfPpkd-Bz112c-LgbsSe yHy1rc eT1oJ']
                                                        );
                                                  ?>

                                               </span>
                                               </li>


                     <?php } ?>




               </ul>


               <!-- <div class="sFI1Df">
                  <hr aria-hidden="true" class="KzUZ2b">
               </div> -->
            </div>

        </div>


    </div>


</section>
<!-- Data list view end -->
