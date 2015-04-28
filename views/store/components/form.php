<?php

use yii\bootstrap\Tabs;

$options_content = $this->render('form_options', [
    'model' => $model,
    'hide_buttons_container' => !empty($hide_buttons_container),
]);

if ($model->add_to_cart_counter_enable) { // Enable tabs

    echo Tabs::widget([
        'items' => [
            [
                'label' => __('General'),
                'content' => $options_content,
                'active' => true
            ],
            [
                'label' => __('Statistics'),
                'content' => $this->render('form_statistics', ['store' => $store]),
                'options' => ['id' => 'myveryownID'],
            ],
        ],
    ]);

} else {

    echo $options_content;

}

