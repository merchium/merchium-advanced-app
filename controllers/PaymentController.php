<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use app\models\Store;
use app\models\Option;

class PaymentController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex($store_id = 0, $order_id = 0)
    {
        $data = Yii::$app->request->post();
        
        if ($data) {

            if (empty($data['shop_domain'])) {
                throw new BadRequestHttpException("Shop domain not pass");
            } elseif (empty($data['order_info']['order_id'])) {
                throw new BadRequestHttpException("Order ID not pass");
            } elseif (empty($data['return_url'])) {
                throw new BadRequestHttpException("Return Url not pass");
            } elseif (empty($data['cancel_url'])) {
                throw new BadRequestHttpException("Cancel Url not pass");
            }
            
            $store = Store::findOne(['domain' => $data['shop_domain']]);
            if (!$store) {
                throw new BadRequestHttpException("Store not found");
            }

            // Save data to option
            $option_name = 'order_' . $data['order_info']['order_id'];
            $option = Option::findOne(['store_id' => $store->id, 'name' => $option_name]);
            if (!$option) {
                $option = new Option;
                $option->link('store', $store);
                $option->name = $option_name;
            }
            $option->value = Json::encode($data);
            $option->save();
            
            return $this->redirect(['index', 'store_id' => $store->id, 'order_id' => $data['order_info']['order_id']]);

        } elseif ($store_id && $order_id) {

            $store = Store::findOne($store_id);
            
            $option = $this->getOption($store_id, $order_id);
            $data = Json::decode($option->value);

            return $this->render('index', [
                'store'    => $store,
                'data'     => $data,
                'order_id' => $order_id,
            ]);
        
        } else {

            throw new BadRequestHttpException();

        }
    }

    public function actionProcess($store_id, $order_id)
    {
        $option = $this->getOption($store_id, $order_id);
        $data = Json::decode($option->value);
        
        // TODO
        
        $option->delete();
        
        return $this->redirect($data['return_url']);
    }

    public function actionCancel($store_id, $order_id)
    {
        $option = $this->getOption($store_id, $order_id);
        $data = Json::decode($option->value);

        // TODO
        
        $option->delete();
        
        return $this->redirect($data['cancel_url']);
    }

    protected function getOption($store_id, $order_id)
    {
        $option = Option::findOne(['store_id' => $store_id, 'name' => 'order_' . $order_id]);
        if (!$option) {
            throw new BadRequestHttpException("Order not found");
        }
        return $option;
    }

}
