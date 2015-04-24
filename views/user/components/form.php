<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\ButtonsContatiner;

$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-10',
            'error' => '',
            'hint' => '',
        ],
    ],
]);

echo $form->field($model, 'username')->textInput(['maxlength' => 255]);

echo $form->field($model, 'email')->textInput(['maxlength' => 255]);

echo $form->field($model, 'password')->passwordInput();

echo $form->field($model, 'fullname')->textInput(['maxlength' => 255]);

echo ButtonsContatiner::widget(['model' => $model]);

ActiveForm::end();

