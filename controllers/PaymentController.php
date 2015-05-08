<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Store;

class PaymentController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $data = Yii::$app->request->post();
        
        $store = Store::findOne($store_id);
        if ($store && $data) {
            // TODO
        }

        return $this->render('index');
    }

}
