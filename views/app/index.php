<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$app_key = Yii::$app->params['appKey'];

?>
<script>
    MerchiumApp.init({
        appKey: '<?= $app_key ?>',
        shopDomain: '<?= $store->domain ?>'
    });

    MerchiumApp.ready(function()
    {
        MerchiumApp.Bar.configure({
            //title: 'New Title',
            buttons: {
                save: {
                    type: 'primary',
                    label: '<?= __('Save changes') ?>',
                    callback: function() {
                        document.forms['options_form'].submit();
                    }
                }
            }
        });
    });
</script>

<div class="row">
<div class="col-md-11">

<?= $this->render('/store/components/form', [
    'model' => $model,
    'store' => $store,
    'hide_buttons_container' => true,
]) ?>

</div>
</div>
