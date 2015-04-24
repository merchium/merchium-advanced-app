<?php

use yii\helpers\Html;

$this->title = __('Store: {store}', [
    'store' => $store->domain,
]);

$this->params['breadcrumbs'][] = ['label' => __('Stores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $store->domain;

?>
<div class="store-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('components/form', [
        'model' => $model,
    ]) ?>

</div>
