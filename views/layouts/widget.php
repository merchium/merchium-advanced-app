<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\widgets\Alert;

$user = Yii::$app->user;

AppAsset::register($this);
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
    <script src='https://market.merchium.ru/js/app.js'></script>
</head>
<body>

<?php $this->beginBody() ?>

<?= Alert::widget() ?>

<div class="container">
    <?= $content ?>
</div>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
