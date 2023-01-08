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
                'label' => 'Store',
                'items' => [
                    ['label' => 'Orders', 'url' => ['/order/index']],
                    ['label' => 'Stores', 'url' => ['/restaurant/index']],
                    ['label' => 'Queue', 'url' => ['/queue/index']],
                    ['label' => 'Restaurant Payment Method', 'url' => ['/restaurant-payment-method/index']],
                    ['label' => 'Opening Hours', 'url' => ['/opening-hour/index']],
                    ['label' => 'Agents', 'url' => ['/agent/index']],
                    ['label' => 'Agent Assignment', 'url' => ['/agent-assignment/index']],
                    ['label' => 'Subscription', 'url' => ['/subscription/index']],
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
            ['label' => 'Customers', 'url' => ['/customer/index']],
            ['label' => 'Debugger', 'url' => Yii::$app->apiUrlManager->getBaseUrl(). '/debug'],
            ['label' => 'Plugn Updates', 'url' => ['/plugn-update/index'] ],
            [
                'label' => 'Settings',
                'items' => [
                    ['label' => 'Add-ons', 'url' => ['/addon/index']],
                    ['label' => 'Staffs', 'url' => ['/staff/index']],
                  ['label' => 'Partner', 'url' => ['/partner/index']],
                  ['label' => 'Country', 'url' => ['/country/index']],
                  ['label' => 'Cities', 'url' => ['/city/index']],
                  ['label' => 'Areas', 'url' => ['/area/index']],
                  ['label' => 'Currency', 'url' => ['/currency/index']],
                  ['label' => 'Payments Method', 'url' => ['/payment-method/index']],
                  ['label' => 'Plans', 'url' => ['/plan/index']],
                  ['label' => 'Bank', 'url' => ['/bank/index']],
                  ['label' => 'Admins', 'url' => ['/admin/index']],
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
