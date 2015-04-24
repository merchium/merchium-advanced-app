<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;

$user = Yii::$app->user;
$controller_id = Yii::$app->controller->id;

AppAsset::register($this);
$this->registerJs(AppAsset::customJs());

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            /**
             * Left nav
             */
            $items = [
                [
                    'label' => __('Stores'),
                    'url' => ['/store/index'],
                    'visible' => !$user->isGuest,
                    'active' => $controller_id == 'store',
                ],
                [
                    'label' => __('Users'),
                    'url' => ['/user/index'],
                    'visible' => !$user->isGuest,
                    'active' => $controller_id == 'user',
                ],
            ];
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'encodeLabels' => false,
                'items' => $items,
            ]);

            /**
             * Right nav
             */
            $items = [];
            if ($user->isGuest) {
                $items[] = [
                    'label' => __('Login'),
                    'url' => ['/site/login']
                ];
            } else {
                $items[] = [
                    'label' => __('Profile'),
                    'url' => ['/user/update', 'id' => $user->identity->id],
                ];
                $items[] = [
                    'label' => __('Logout') . ' (' . $user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items,
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
            <p class="pull-left">&copy; <?= Yii::$app->params['companyName'] ?> <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
