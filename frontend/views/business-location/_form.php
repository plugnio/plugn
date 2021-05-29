<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Country;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */
/* @var $form yii\widgets\ActiveForm */
$locateImgUrl = Yii::$app->urlManager->getBaseUrl() . '/img/locate-mp.svg';

$latitude = $model->latitude;
$longitude = $model->longitude;


$location = 'Kuwait,City';

if ($model->business_location_id) {
  if($model->latitude && $model->longitude)
    $location = $model->latitude . ',' . $model->longitude;
  else {
    if($model->country_id == 12) // Bahrain
      $location = 'Manama';
    else if($model->country_id == 129) // KSA
      $location = 'Riyadh';
  }
}else {
  if($store_model->country_id == 12) // Bahrain
    $location = 'Manama';
  else if($store_model->country_id == 129) // KSA
    $location = 'Riyadh';
}

?>

<script type='text/javascript'>

function initMap() {

  var theLat      = '<?= $latitude ?>';
  var theLng      = '<?= $longitude ?>';


  var zoom = 13;


  if(!theLat && !theLng){

    if (navigator.geolocation) {

      navigator.geolocation.getCurrentPosition(
        (position) => {

          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          map.setCenter(pos);
        }, function(error) {


          getLatAndLng();
        }
      );
    } else {

      getLatAndLng();

    }
  } else {
    zoom = 20;
  }


  if(!theLat && !theLng){
    getLatAndLng();
  }

  var myLatlng = new google.maps.LatLng(theLat,theLng);


  const map = new google.maps.Map(document.getElementById("map"), {
    center: myLatlng,
    zoom: zoom,
    zoomControl: true, // a way to quickly hide all controls
    disableDefaultUI: true, // a way to quickly hide all controls

  });


  const card = document.getElementById("searchGrp");
  const input = document.getElementById("placeSearch");


  const options = {
    componentRestrictions: { country: ["kw", "sa", "bh"]},
    fields: ["formatted_address", "geometry", "name"],
    origin: map.getCenter(),
    strictBounds: false,
    types: ["establishment"],
  };
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(card);
  const autocomplete = new google.maps.places.Autocomplete(input, options);
  // Bind the map's bounds (viewport) property to the autocomplete object,
  // so that the autocomplete requests use the current map bounds for the
  // bounds option in the request.
  autocomplete.bindTo("bounds", map);

  const infowindow = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");
  infowindow.setContent(infowindowContent);

  autocomplete.addListener("place_changed", () => {
    infowindow.close();
    // marker.setVisible(false);
    const place = autocomplete.getPlace();

    if (!place.geometry || !place.geometry.location) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      // window.alert("No details available for input: '" + place.name + "'");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }

    // infowindowContent.children["place-name"].textContent = place.name;
    // infowindowContent.children["place-address"].textContent =
    //   place.formatted_address;
  });


  map.addListener('dragend', function() {

        map.setCenter(this.center);

       document.getElementById("businesslocation-latitude").value =  this.center.lat();
       document.getElementById("businesslocation-longitude").value =  this.center.lng();

});

}


function getLatAndLng() {

  if('<?= $location ?>' == 'Kuwait,City'){
    theLat      = '29.375859';
    theLng      = '47.9774052';
  } else if ('<?= $location ?>' == 'Manama'){
    theLat      = '26.2235305';
    theLng      = '50.5875935';
  } else if ('<?= $location ?>' == 'Riyadh'){
    theLat      = '24.7135517';
    theLng      = '46.6752957';
  }
}


</script>

<style>
  .modal-body{
    padding: 0;
    height: 475px;
  }

  @media (min-width: 576px){
    .modal-dialog {
        max-width: 600px !important;
        margin: 1.75rem auto !important;
    }
  }

  .modal-header{
    padding: 16px !important;
  }

  .map-marker-img{
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 5;
    width: 30px;
    height: 45px;
    z-index: 5;
    transform: translate(-50%,-100%);
    width: 27px !important;
    height: 43px !important;
  }

  .pac-container{
    z-index: 999999 !important;
  }
</style>

  <?php


      $form = ActiveForm::begin();

  ?>

  <div class="card">

      <div class="card-header">
        <h3>Business location Info </h3>
      </div>

      <div class="business-location-form card-body">

        <?php

              $countryQuery = Country::find()->asArray()->all();
              $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');



          ?>


          <?= $form->field($model, 'business_location_name')->textInput(['maxlength' => true, 'placeholder' => "الفرع الرئيسي"])->label('Location name in Arabic *') ?>
          <?= $form->field($model, 'business_location_name_ar')->textInput(['maxlength' => true, 'placeholder' => "الفرع الرئيسي"])->label('Location name in Arabic *') ?>



          <?=
            $form->field($model, 'country_id')->dropDownList($countryArray, [
                'prompt' => 'Choose country name...',
                'class' => 'form-control select2',
                'multiple' => false
            ])->label('Located in *');
          ?>



<label class="control-label" for="businesslocation-country_id">Business Address</label>

          <!-- Vertical modal -->
          <div class="vertical-modal-ex">
              <!-- <button type="button" class="btn btn-outline-primary"> -->
                <img  data-toggle="modal" data-target="#exampleModalCenter"
                style="width: 100%;cursor: pointer;"
                src=<?= "http://maps.googleapis.com/maps/api/staticmap?center=" .  $location ."&scale=2&style=feature:poi|visibility:off&zoom=16&size=430x50&key=AIzaSyCFeQ-wuP5iWVRTwMn5nZZeOE8yjGESFa8" ?> >

              <!-- </button> -->
              <!-- Modal -->
              <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document" style="height:100%">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalCenterTitle">Business Address</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                              </button>
                          </div>


                          <div class="modal-body">
                              <img alt="" class="map-marker-img" src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/marker-icon.png' ?>" draggable="false" usemap="#gmimap0" style="user-select: none;border: 0px;padding: 0px;margin: 0px;max-width: none;">

                              <div class="searchGrp map-search-box" id="searchGrp">
                                    <input type="text" class="form-control pac-target-input" placeholder="Search for area, block, street name..." style="padding-right:25px;height:40px;" id="placeSearch" autocomplete="off" autocorrect="off">
                              </div>

                            <div id="map">
                            </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Save</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <?= $form->field($model, 'address')->textInput(['maxlength' => true,'style' => 'display:none'])->label(false) ?>
          <?= $form->field($model, 'latitude')->textInput(['maxlength' => true,'style' => 'display:none'])->label(false) ?>
          <?= $form->field($model, 'longitude')->textInput(['maxlength' => true,'style' => 'display:none'])->label(false) ?>


      </div>

</div>

<div class="card">
  <div class="card-header">
    <h3>Delivery Integration Info </h3>
  </div>

  <div class="row">
    <div class="col-12 col-sm-6 col-lg-6">

            <div class="card">
                <div class="card-content">
                    <div class="card-body" style="padding-bottom: 0px;">
                        <h4 class="card-title">Mashkor Delivery</h4>
                        <a class="mb-4 text-primary-base hover:text-primary-700" rel="noopener noreferrer" target="_blank" href="https://www.plugn.io/local-delivery/kuwait/mashkor">
                          <span>Learn more about Mashkor Delivery
                            <svg width="24" height="24" viewBox="0 0 24 24" class="inline"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="#3852CA" fill-rule="nonzero" d="M7.333 7.2h4a.8.8 0 0 1 .1 1.594l-.1.006h-4a.488.488 0 0 0-.377.156.481.481 0 0 0-.15.289l-.006.088v7.334c0 .412.074.518.436.531l.097.002h7.334c.412 0 .518-.074.531-.436l.002-.097v-4a.8.8 0 0 1 1.594-.1l.006.1v4c0 1.273-.734 2.062-1.963 2.128l-.17.005H7.333c-1.273 0-2.062-.734-2.128-1.963l-.005-.17V9.333c0-.58.214-1.098.625-1.508a2.077 2.077 0 0 1 1.317-.617l.191-.008h4-4zM14 5.2h4.029l.052.004L18 5.2a.805.805 0 0 1 .566.234l-.077-.067a.804.804 0 0 1 .305.533l.002.017a.805.805 0 0 1 .004.065V10a.8.8 0 0 1-1.594.1L17.2 10l-.001-2.069-5.967 5.968a.8.8 0 0 1-1.041.077l-.09-.077a.8.8 0 0 1 0-1.131l5.968-5.969L14 6.8a.8.8 0 0 1-.1-1.594L14 5.2h4-4z"></path></g>
                            </svg>
                          </span>

                        </a>

                    </div>

                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                              <?= $form->field($model, 'mashkor_branch_id',[
                                'labelOptions' => [ 'style' => 'font-size: 0.875rem; '],
                                'options' => ['style' => 'margin-bottom:  0px;'],
                              ]
                            )->textInput(['maxlength' => true, 'style' => 'margin-bottom:  0px;','placeholder' => 'Mashkor Branch id']) ?>
                            </div>
                    </div>

                </div>
            </div>

      </div>

      <div class="col-12 col-sm-6 col-lg-6">

              <div class="card">
                  <div class="card-content">
                      <div class="card-body" style="padding-bottom: 0px;">
                          <h4 class="card-title">Armada Delivery</h4>
                          <a class="mb-4 text-primary-base hover:text-primary-700" rel="noopener noreferrer" target="_blank" href="https://www.plugn.io/local-delivery/kuwait/armada">
                            <span class="block w-full inline" style="direction: ltr;">Learn more about Armada Delivery
                              <svg width="24" height="24" viewBox="0 0 24 24" class="inline"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="#3852CA" fill-rule="nonzero" d="M7.333 7.2h4a.8.8 0 0 1 .1 1.594l-.1.006h-4a.488.488 0 0 0-.377.156.481.481 0 0 0-.15.289l-.006.088v7.334c0 .412.074.518.436.531l.097.002h7.334c.412 0 .518-.074.531-.436l.002-.097v-4a.8.8 0 0 1 1.594-.1l.006.1v4c0 1.273-.734 2.062-1.963 2.128l-.17.005H7.333c-1.273 0-2.062-.734-2.128-1.963l-.005-.17V9.333c0-.58.214-1.098.625-1.508a2.077 2.077 0 0 1 1.317-.617l.191-.008h4-4zM14 5.2h4.029l.052.004L18 5.2a.805.805 0 0 1 .566.234l-.077-.067a.804.804 0 0 1 .305.533l.002.017a.805.805 0 0 1 .004.065V10a.8.8 0 0 1-1.594.1L17.2 10l-.001-2.069-5.967 5.968a.8.8 0 0 1-1.041.077l-.09-.077a.8.8 0 0 1 0-1.131l5.968-5.969L14 6.8a.8.8 0 0 1-.1-1.594L14 5.2h4-4z"></path></g>
                              </svg>
                            </span>

                          </a>

                      </div>
                      <div class="card-body">
                          <form class="form">
                              <div class="form-body">
                                <?= $form->field($model, 'armada_api_key',[
                                  'labelOptions' => [ 'style' => 'font-size: 0.875rem; '],
                                  'options' => ['style' => 'margin-bottom:  0px;'],
                                ]
                              )->textInput(['maxlength' => true, 'style' => 'margin-bottom:  0px;','placeholder' => 'Armada Api Key']) ?>
                              </div>
                      </div>
                  </div>
              </div>

        </div>

    </div>

</div>



<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>


<?php ActiveForm::end(); ?>


<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFeQ-wuP5iWVRTwMn5nZZeOE8yjGESFa8&callback=initMap&libraries=places&v=weekly" async></script>
