<?php

use yii\helpers\Html;


echo Html::tag('h3', __('Payment'));

echo $form->field($model, 'payment_enable')->checkbox([], false);

