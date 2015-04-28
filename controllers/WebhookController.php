<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Store;

class WebhookController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex($store_id)
    {
        $data = Yii::$app->request->post();
        
        $store = Store::findOne($store_id);
        if ($store && $data) {
            $store->webhook($data);
        }

        Yii::$app->response->format = 'json';
        return ['status' => 'ok'];
    }

}
