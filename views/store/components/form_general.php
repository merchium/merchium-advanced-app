<?php

use yii\helpers\Html;
use kartik\date\DatePicker;


echo Html::tag('h3', __('Snowfall'));

echo $form->field($model, 'snowfall_enable')->checkbox([], false);

echo Html::tag('h3', __('Welcome popup'));

echo $form->field($model, 'welcome_popup_enable')->checkbox([], false);

echo $form->field($model, 'welcome_popup_title');

echo $form->field($model, 'welcome_popup_content')->textarea(['rows' => 4]);

