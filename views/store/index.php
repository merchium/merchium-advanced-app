<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\grid\GridView;
use app\widgets\ActionsDropdown;

$this->title = Yii::t('app', 'Stores');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index">

    <div class="pull-right">
        <?= ActionsDropdown::widget([
            'layout' => 'info',
            'items' => [
                ['label' => __('Delete selected'), 'url' => Url::to(['delete']), 'linkOptions' => [
                    'data-c-process-items' => 'ids',
                    'data-confirm' => __('Are you sure you want to delete selected items?'),
                    'data-method' => 'post',
                ]],
            ],
        ]) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('components/search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            'id',
            'domain',
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'app\widgets\grid\ActionColumn', 'size' => 'xs'],
        ],
    ]); ?>

</div>
