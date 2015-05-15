<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
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

echo Tabs::widget([
    'items' => [
        [
            'label' => __('General'),
            'content' => $this->render('form_general', [
                'model' => $model,
                'form' => $form,
            ]),
            'active' => true
        ],
        [
            'label' => __('Cart'),
            'content' => $this->render('form_statistics', [
                'model' => $model,
                'form' => $form,
                'store' => $store
            ]),
        ],
        [
            'label' => __('Payment'),
            'content' => $this->render('form_payment', [
                'model' => $model,
                'form' => $form,
                'store' => $store
            ]),
        ],
    ],
]);

if (empty($hide_buttons_container)) {
    echo ButtonsContatiner::widget(['model' => $model, 'removeLink' => false]);
}

ActiveForm::end();
