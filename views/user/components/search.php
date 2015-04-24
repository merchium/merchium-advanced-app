<?php

use yii\helpers\Html;
use app\widgets\SearchForm;

?>

<div class="user-search">

    <?php $form = SearchForm::begin(); ?>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'username') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'email') ?>
        </div>
        
    </div>

    <?php SearchForm::end(); ?>

</div>
