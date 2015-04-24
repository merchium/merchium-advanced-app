<?php

use yii\helpers\Html;
use app\widgets\SearchForm;

?>

<div class="state-search">

    <?php $form = SearchForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'domain') ?>
        
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'id') ?>

        </div>
    </div>

    <?php SearchForm::end(); ?>

</div>
