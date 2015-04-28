<?php

use yii\helpers\Html;

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

