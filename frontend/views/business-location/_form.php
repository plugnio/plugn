<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */
/* @var $form yii\widgets\ActiveForm */
$locateImgUrl = Yii::$app->urlManager->getBaseUrl() . '/img/locate-mp.svg';
?>
<script type='text/javascript'>

function initMap() {

  var theLat      = 29.3771;
  var theLng      = 47.977631;

  var myLatlng = new google.maps.LatLng(theLat,theLng);

  const map = new google.maps.Map(document.getElementById("map"), {
    center: myLatlng,
    zoom: 13,
    zoomControl: true, // a way to quickly hide all controls
    disableDefaultUI: true, // a way to quickly hide all controls

  });

  let marker  = new google.maps.Marker({
       position: myLatlng,
       map: map,
       draggable: true
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
   new google.maps.Marker({
    map,
    anchorPoint: new google.maps.Point(0, -29),
       draggable: true
  });
  autocomplete.addListener("place_changed", () => {
    infowindow.close();
    marker.setVisible(false);
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
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
    infowindowContent.children["place-name"].textContent = place.name;
    infowindowContent.children["place-address"].textContent =
      place.formatted_address;
    infowindow.open(map, marker);
  });


    google.maps.event.addListener(marker, 'dragend', function() {

       if (confirm("Are You Sure You Want To Move this marker?")) {
            var positionStartLatNew = this.position.lat();
            var positionStartLngNew = this.position.lng();

            map.setCenter(this.position);
            map.setZoom(17);
            document.getElementById('end').innerHTML = "Lat end : " + positionStartLatNew + ", " + "Lng end : " + positionStartLngNew;
       } else {
            google.maps.event.addListener(marker, 'dragstart', function() {
            var positionStartLat = this.position.lat();
            var positionStartLng = this.position.lng();
            map.setCenter(this.position);
            map.setZoom(17);
            document.getElementById('start').innerHTML = "Lat start : " + positionStartLat + ", " + "Lng start : " + positionStartLng;

            });
       }
       
       document.getElementById("businesslocation-latitude").value =  this.position.lat();
       document.getElementById("businesslocation-longitude").value =  this.position.lng();



  });

  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
 }
}
</script>


  <div class="card">
      <div class="business-location-form card-body">

        <?php

              $countryQuery = Country::find()->asArray()->all();
              $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');


              $form = ActiveForm::begin();
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




          <?= $form->field($model, 'address')->textInput(['maxlength' => true,'style' => 'display:none'])->label(false) ?>
          <?= $form->field($model, 'latitude')->textInput(['maxlength' => true,'style' => 'display:none'])->label(false) ?>
          <?= $form->field($model, 'longitude')->textInput(['maxlength' => true,'style' => 'display:none'])->label(false) ?>



          <div class="form-group">
              <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>


          </div>
<?php ActiveForm::end(); ?>
      </div>

</div>



      <p><span id="start"></span></p>
      <p><span id="end"></span></p>

        <div class="searchGrp map-search-box" id="searchGrp">
              <input type="text" class="form-control pac-target-input" placeholder="Search for area, block, street name..." style="padding-right:25px;height:40px;" id="placeSearch" autocomplete="off">
        </div>

      <div id="map"></div>
      <div id="infowindow-content">
        <span id="place-name" class="title"></span><br />
        <span id="place-address"></span>
      </div>
