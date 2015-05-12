<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->params['applicationName'] . ': ' . __('Checkout');

$product_index = 0;

// pd($data['order_info']);

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-sm-3">
        
        <div class="panel panel-info">
            <div class="panel-heading">Totals</div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>Subtotal</td>
                        <td><?= $data['order_info']['subtotal'] ?></td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td><?= $data['order_info']['discount'] ?></td>
                    </tr>
                    <tr>
                        <td>Shipping</td>
                        <td><?= $data['order_info']['shipping_cost'] ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><?= $data['order_info']['total'] ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">User data</div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>Name</td>
                        <td><?= $data['order_info']['firstname'] ?> <?= $data['order_info']['lastname'] ?></td>
                    </tr>
                    <tr>
                        <td>E-mail</td>
                        <td><?= $data['order_info']['email'] ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><?= $data['order_info']['b_address'] ?> <?= $data['order_info']['b_address_2'] ?></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td><?= $data['order_info']['b_city'] ?></td>
                    </tr>
                    <tr>
                        <td>Zipcode</td>
                        <td><?= $data['order_info']['b_zipcode'] ?></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    <div class="col-sm-9">
        
        <h3>Products</h3>
        <table class="table">
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($data['order_info']['products'] as $product) : ?>
                <tr>
                    <td><?= ++$product_index ?></td>
                    <td><?= $product['product'] ?></td>
                    <td><?= $product['price'] ?></td>
                    <td><?= $product['amount'] ?></td>
                    <td><?= $product['subtotal'] ?></td>
                </tr>
            <?php endforeach ?>
        </table>

        <div class="jumbotron">
            <a class="btn btn-success btn-lg" href="<?= Url::to(['process', 'store_id' => $store->id, 'order_id' => $order_id]) ?>" role="button">Process</a>
            <a class="btn btn-danger btn-lg" href="<?= Url::to(['cancel', 'store_id' => $store->id, 'order_id' => $order_id]) ?>" role="button">Cancel</a>
        </div>

    </div>

</div>
