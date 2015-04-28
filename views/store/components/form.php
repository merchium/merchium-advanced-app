<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use app\widgets\ButtonsContatiner;


$form = ActiveForm::begin([
    'options' => [
        'name' => 'options_form',
    ],
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-3',
            'wrapper' => 'col-sm-9',
            'error' => '',
            'hint' => '',
        ],
    ],
]);

// Fields list

echo Html::tag('h3', __('Snowfall'));

echo $form->field($model, 'snowfall_enable')->checkbox([], false);

echo Html::tag('h3', __('Welcome popup'));

echo $form->field($model, 'welcome_popup_enable')->checkbox([], false);

echo $form->field($model, 'welcome_popup_title');

echo $form->field($model, 'welcome_popup_content')->textarea(['rows' => 4]);

echo Html::tag('h3', __('Statistics'));

echo $form->field($model, 'add_to_cart_counter_enable')->checkbox([], false);

// \Fields list


if (empty($hide_buttons_container)) {
    echo ButtonsContatiner::widget(['model' => $model, 'removeLink' => false]);
}

ActiveForm::end();
