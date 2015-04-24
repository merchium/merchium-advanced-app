<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->params['applicationName'];

$scheme = Yii::$app->request->isSecureConnection ? 'https' : 'http';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome</h1>

        <p class="lead">Merchium Advanced Example Application</p>
    </div>

    <div class="body-content">

        <?php if (!Yii::$app->user->isGuest) : ?>

            <h1 class="page-header"><?= __('Dashboard') ?></h1>

            <ul class="list-group">
            <?php
                foreach ($dashboard as $row) {
                    $span = Html::tag('span', $row['count'], ['class' => 'badge']);
                    $href = Html::a($row['name'], $row['link']);
                    echo Html::tag('li', $span . $href, ['class' => 'list-group-item']);
                }
            ?>
            </ul>

            <h1 class="page-header"><?= __('Links') ?></h1>

            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?= __('App admin page URL') ?></label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" value="<?= Url::to(['/app'], $scheme) ?>" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?= __('App install page URL') ?></label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" value="<?= Url::to(['app/install'], $scheme) ?>" readonly />
                    </div>
                </div>
            </form>

        <?php endif; ?>

    </div>

</div>
