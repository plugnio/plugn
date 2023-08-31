<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap <?php if(!Yii::$app->user->isGuest) echo 'role-' . Yii::$app->user->identity->admin_role; ?>">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = array_merge($menuItems, [
            [
                'label' => 'Statistics',
                'items' => [
                    ['label' => 'Numbers', 'url' => ['/stats/index']],
                    ['label' => 'Graphs', 'url' => ['/stats/graph']],
                    ['label' => 'Payment Gateways', 'url' => ['/stats/payment-gateways']],
                    ['label' => 'Sales & Revenue', 'url' => ['/stats/sales']],
                    ['label' => 'Store Closer & Retention', 'url' => ['/stats/store-retention']],
                    ['label' => 'Customer Funnel', 'url' => ['/stats/customer-funnel']],
                ]
            ],
            [
                'label' => 'Report',
                'items' => [
                    ['label' => 'Plugn commission ', 'url' => ['/report/index']],
                    ['label' => 'COD Orders', 'url' => ['/report/cash-on-delivery']],
                ]
            ],
            [
                'label' => 'Store',
                'items' => [
                    ['label' => 'Invoices', 'url' => ['/restaurant-invoice/index']],
                    ['label' => 'Orders', 'url' => ['/order/index']],
                    ['label' => 'Stores', 'url' => ['/restaurant/index']],
                    ['label' => 'Domain Request', 'url' => ['/restaurant-domain-request/index']],
                    ['label' => 'Queue', 'url' => ['/queue/index']],
                    ['label' => 'Restaurant Payment Method', 'url' => ['/restaurant-payment-method/index']],
                    ['label' => 'Opening Hours', 'url' => ['/opening-hour/index']],
                    ['label' => 'Agents', 'url' => ['/agent/index']],
                    ['label' => 'Agent Assignment', 'url' => ['/agent-assignment/index']],
                    ['label' => 'Subscription', 'url' => ['/subscription/index']],
                    ['label' => 'Customers', 'url' => ['/customer/index']],
                    ['label' => 'Debugger', 'url' => Yii::$app->apiUrlManager->getBaseUrl(). '/debug'],
                ]
            ],
            [
                'label' => 'Payment',
                'items' => [
                    ['label' => 'Payment gateway Queue', 'url' => ['/payment-gateway-queue/index']],
                    ['label' => 'Refund', 'url' => ['/refund/index']],
                    ['label' => 'Payment', 'url' => ['/payment/index']],
                    ['label' => 'Subscription Payment', 'url' => ['/subscription-payment/index']],
                ]
            ],
            [
                'label' => 'Marketing',
                'items' => [
                    ['label' => 'Vendor campaign', 'url' => ['/campaign/index']],
                    ['label' => 'Vendor Email campaign', 'url' => ['/vendor-campaign/index']],
                    ['label' => 'Pre-built email template', 'url' => ['/prebuilt-email-template/index']],
                    ['label' => 'Vendor email template', 'url' => ['/vendor-email-template/index']],
                    ['label' => 'Plugn Updates', 'url' => ['/plugn-update/index'] ],
                ]
            ],
            [
                'label' => 'Settings',
                'items' => [
                    ['label' => 'Add-ons', 'url' => ['/addon/index']],
                    ['label' => 'Staffs', 'url' => ['/staff/index']],
                  ['label' => 'Partner', 'url' => ['/partner/index']],
                  ['label' => 'Country', 'url' => ['/country/index']],
                  ['label' => 'States', 'url' => ['/state/index']],
                  ['label' => 'Cities', 'url' => ['/city/index']],
                  ['label' => 'Areas', 'url' => ['/area/index']],
                  ['label' => 'Currency', 'url' => ['/currency/index']],
                  ['label' => 'Payment Methods', 'url' => ['/payment-method/index']],
                  ['label' => 'Shipping Methods', 'url' => ['/shipping-method/index']],
                  ['label' => 'Plans', 'url' => ['/plan/index']],
                  ['label' => 'Bank', 'url' => ['/bank/index']],
                  ['label' => 'Admins', 'url' => ['/admin/index']],
                  ['label' => 'Plugn Settings', 'url' => ['/setting/update']],
                ],
            ],
        ]);

        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->admin_name . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= Alert::widget() ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
