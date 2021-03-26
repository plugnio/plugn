<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">
    <h1>
        <?= Html::encode($this->title) ?>
        <span class="badge">
            <?= $model->status ?>
        </span>
        <?= $model->is_tap_enable ? '' : Html::a('Create Tap account', ['create-tap-account', 'restaurant_uuid' => $model->restaurant_uuid], ['class' => 'btn btn-success']) ?>


    </h1>
    <?= Yii::$app->session->getFlash('errorResponse') ? '<h2>'. Yii::$app->session->getFlash('errorResponse') . '</h2>' : '' ?>


    <p>

        <?=
          Html::a($model->hide_request_driver_button == 1 ? 'Display request driver button' : 'Hide request driver button',
          [
            $model->hide_request_driver_button == 1 ?  'display-request-driver-button' : 'hide-request-driver-button'
         , 'id' => $model->restaurant_uuid], ['class' => $model->hide_request_driver_button == 0 ? 'btn btn-success' : 'btn btn-danger'])
         ?>
        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this store?',
                'method' => 'post',
            ],
        ])
        ?>

        <?= Html::a('Update sitemap', ['update-sitemap', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-warning']) ?>

    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
          [
              'label' => 'is_tap_enable',
              'value' => function ($data) {
                  return $data->is_tap_enable ? 'Yes' : 'No';
              },
              'format' => 'raw'
          ],
          [
              'label' => 'is_myfatoorah_enable',
              'value' => function ($data) {
                  return $data->is_myfatoorah_enable ? 'Yes' : 'No';
              },
              'format' => 'raw'
          ],
            'tap_queue_id',
            'version',
            'sitemap_require_update',
            'country.country_name',
            [
                'label' => 'Store currency',
                'value' => function ($data) {
                  return $data->currency->title;
                }
            ],
            [
                'label' => 'Hide Request driver button',
                'value' => function ($data) {
                  return $data->hide_request_driver_button  == 1 ? 'Yes' : 'No';
                }
            ],
            'restaurant_uuid',
            [
                'label' => 'Payment Methods',
                'value' => function ($data) {
                    $paymentMethods = '';

                    foreach ($data->getPaymentMethods()->all() as $key => $paymentMethod) {

                        if ($key == 0)
                            $paymentMethods .=  $paymentMethod['payment_method_name'] ;
                        else
                            $paymentMethods .= ', ' .  $paymentMethod['payment_method_name'] ;
                    }

                    return $paymentMethods;
                },
                'format' => 'raw'
            ],
            'site_id',
            'company_name',
            'name',
            'name_ar',
            'tagline',
            'tagline_ar',
            'app_id',
            'status',
            'business_type',
            'restaurant_domain',
            'thumbnail_image',
            'logo',
            'identification_file_front_side',
            'identification_file_back_side',
            [
                'attribute' => 'thumbnail_image',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->getRestaurantThumbnailImageUrl());
                },
            ],
            [
                'attribute' => 'logo',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->getRestaurantLogoUrl());
                },
            ],
            // [
            //     'label' => 'Support Delivery',
            //     'value' => function ($data) {
            //         return $data->support_delivery ? 'Yes' : 'No';
            //     },
            //     'format' => 'raw'
            // ],
            // [
            //     'label' => 'Support Pick up',
            //     'value' => function ($data) {
            //         return $data->support_pick_up ? 'Yes' : 'No';
            //     },
            //     'format' => 'raw'
            // ],
            'phone_number',
            'restaurant_email:email',
            'instagram_url:url',
            'iban',
            'restaurant_created_at',
            'restaurant_updated_at',
            'platform_fee:percent',
            'warehouse_fee',
            'facebook_pixil_id',
            'google_analytics_id',
            [
              'attribute' => 'Owner name',
              'format' => 'html',
              'value' => function ($data) {
                  return  $data->owner_first_name && $data->owner_last_name ? $data->owner_first_name . ' ' . $data->owner_last_name : null;
              },
            ],
            'owner_email',
            'owner_number',
            'vendor_sector',
            'business_id',
            'developer_id',
            'business_entity_id',
            'merchant_id',
            'authorized_signature_file_id',
            'commercial_license_file_id',
            'identification_file_id_front_side',
            'identification_file_id_back_side',
            'identification_title',
            'commercial_license_title',
            'authorized_signature_title',


            'wallet_id',
            'operator_id',

            'live_api_key',
            'test_api_key',

            'live_public_key',
            'test_public_key',

            'store_branch_name',
            'custom_css:text',
        ],
    ])
    ?>


    <h2>Theme color</h2>

    <div class="card">

        <?=
        GridView::widget([
            'dataProvider' => $storeThemeColors,
            'columns' => [
                'primary',
                'secondary',
                'tertiary',
                'light',
                'medium',
                'dark',
            ],
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>

    </div>



</div>
