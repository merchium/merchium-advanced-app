<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

Hello <?= $user->username ?>!

Follow the link below to reset your password:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
