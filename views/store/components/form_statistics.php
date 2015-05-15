<?php

use yii\helpers\Html;


echo Html::tag('h3', __('Cart'));

echo $form->field($model, 'add_to_cart_counter_enable')->checkbox([], false);

$stats = $store->getAddToCartStats();
if ($stats) {

    echo Html::tag('h3', __('Statistics'));

    echo Html::beginTag('table', ['class' => 'table table-striped']);
    
    foreach ($stats as $product) {
        $rows = [
            Html::tag('td', $product['product']),
            Html::tag('td', Html::tag('span', $product['added_to_cart'], ['class' => 'badge'])),
        ];
        echo Html::tag('tr', implode(' ', $rows));
    }
    
    echo Html::endTag('table');

}

