<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\WebLink;

/* @var $this yii\web\View */
/* @var $model common\models\WebLink */
/* @var $form yii\widgets\ActiveForm */

$js = "
let webLinkTypeInput = $('#webLinkTypeInput');



// On Change of web link type input
webLinkTypeInput.change(function(){
    let selection = $(this).val();
    if(selection == 1) {
        document.getElementById('group-text').innerHTML='https://example.com/users/';
    }
     // facebook
     else if (selection == 2){
      document.getElementById('group-text').innerHTML='https://www.facebook.com/';
    }

     // instagram
     else if (selection == 3){
      document.getElementById('group-text').innerHTML='https://www.instagram.com/';
    }

     // twitter
     else if (selection == 4){
      document.getElementById('group-text').innerHTML='https://www.twitter.com/';
    }

     // snapchat
     else if (selection == 5){
      document.getElementById('group-text').innerHTML='https://www.snapchat.com/add/';
    }

     // whatsApp
     else if (selection == 6){
      document.getElementById('group-text').innerHTML='+965';
      document.getElementById('weblink-url_txt').innerHTML='Phone Number';

    }

     // Email
     else if (selection == 7){
      document.getElementById('weblink-url_txt').innerHTML='Email';
      document.getElementById('group-text').innerHTML='example@domain.com';
    }


});

// On page load
    let selection = webLinkTypeInput.val();
    if(selection == 1) {
        document.getElementById('group-text').innerHTML='https://example.com/users/';
    }
     // facebook
     else if (selection == 2){
      document.getElementById('group-text').innerHTML='https://www.facebook.com/';
    }

     // instagram
     else if (selection == 3){
      document.getElementById('group-text').innerHTML='https://www.instagram.com/';
    }

     // twitter
     else if (selection == 4){
      document.getElementById('group-text').innerHTML='https://www.twitter.com/';
    }

     // snapchat
     else if (selection == 5){
      document.getElementById('group-text').innerHTML='https://www.snapchat.com/add/';
    }

     // whatsApp
     else if (selection == 6){
      document.getElementById('group-text').innerHTML='+965';
      document.getElementById('weblink-url_txt').innerHTML='Phone Number';
    }

    // Email
    else if (selection == 7){
    document.getElementById('weblink-url_txt').innerHTML='Email';
    document.getElementById('group-text').innerHTML='example@domain.com';
    }
";


$this->registerJs($js);



if (!$model->isNewRecord) {
    echo Html::a('Delete', ['delete', 'id' => $model->web_link_id, 'restaurantUuid' => $model->restaurant_uuid], [
        'class' => 'btn btn-danger  mr-1 mb-1',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]);
}
?>

<div class="card">
    <div class="card-body voucher-form">

        <?php
          $form = ActiveForm::begin([
                      'id' => 'dynamic-form',
                      'errorSummaryCssClass' => 'alert alert-danger'
          ]);
        ?>
        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


          <?=
          $form->field($model, 'web_link_type')->dropDownList([
            WebLink::WEB_LINK_TYPE_WEBSITE_URL => 'Website URL',
            WebLink::WEB_LINK_TYPE_FACEBOOK => 'Facebook',
            WebLink::WEB_LINK_TYPE_INSTAGRAM => 'Instagram',
            WebLink::WEB_LINK_TYPE_TWITTER => 'Twitter',
            WebLink::WEB_LINK_TYPE_SNAPCHAT => 'Snapchat',
            WebLink::WEB_LINK_TYPE_WHATSAPP => 'WhatsApp',
            WebLink::WEB_LINK_TYPE_EMAIL => 'Email',
          ], [
              'id' => 'webLinkTypeInput'
          ]);
          ?>

          <div class="row">
              <div class="col-12 col-sm-6 col-lg-6">
                <?= $form->field($model, 'web_link_title')->textInput(['maxlength' => true]); ?>
              </div>

              <div class="col-12 col-sm-6 col-lg-6">
                <?= $form->field($model, 'web_link_title_ar')->textInput(['maxlength' => true]); ?>
              </div>
          </div>


          <?= $form->field($model, 'url', [
            'labelOptions' => ['id' => 'weblink-url_txt'],
              'template' => "{label}"

              . "<div  class='input-group'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text' id='group-text'></span>
                  </div>
                    {input}
                </div>
              "

              . "{error}{hint}"
          ])->textInput(['maxlength' => true,'style' => '    border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;'])?>



          <div class="form-group" style="background: #f4f6f9; padding-bottom: 0px; margin-bottom: 0px;  background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
          </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
