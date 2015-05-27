<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\widgets\ButtonsContatiner;

$this->title = Yii::t('app', 'Send admin notification');
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', Html::encode($this->title));
$form = ActiveForm::begin([
    'options' => [
        'name' => 'notification_form',
    ],
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-10',
            'error' => '',
            'hint' => '',
        ],
    ],
]);

$content = [];
foreach ($stores as $store) {
    $content[] = Html::tag('a', $store->domain, ['href' => Url::to(['update', 'id' => $store->id])]);
}
$content = Html::tag('div', implode('<br /> ', $content), ['class' => 'col-sm-10 checkbox']);
$label = Html::tag('label', __('Stores'), ['class' => 'control-label col-sm-2']);
echo Html::tag('div', $label . ' ' . $content, ['class' => 'form-group']);

echo $form->field($model, 'type')->dropDownList([
    'E' => __('Error'),
    'W' => __('Warning'),
    'N' => __('Notice'),
    'I' => __('Info'),
]);

echo $form->field($model, 'title')->textInput();

echo $form->field($model, 'message')->textarea();

echo $form->field($model, 'message_state')->dropDownList([
    '' => ' -- ',
    'S' => __("Notification will be displayed unless it's closed"),
    'K' => __('Only once'),
    'I' => __('Will be closed by timer'),
]);

$btn = Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'name' => 'notification-button']);
echo Html::tag('div', $btn, ['class' => 'form-group panel-footer']);

ActiveForm::end();
