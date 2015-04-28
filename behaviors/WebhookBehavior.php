<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Inflector;
use yii\helpers\Json;
use app\models\Option;

class WebhookBehavior extends Behavior
{
    public function webhook($data)
    {
        if (!empty($data['event'])) {
            $method = lcfirst(Inflector::camelize($data['event']));
            if (method_exists($this, $method)) {
                return $this->$method($data);
            }
        }

        return false;
    }

    protected function addToCart($data)
    {
        if (empty($data['product_ids'])) {
            return false;
        }

        $store = $this->owner;
        $options = $store->getOptionsByName();
        
        if (!empty($options['add_to_cart_counter_statistics'])) {
            $option = $options['add_to_cart_counter_statistics'];
        } else {
            $option = new Option;
            $option->name = 'add_to_cart_counter_statistics';
            $option->link('store', $store);
        }

        $stats = [];
        if ($option->value) {
            $stats = Json::decode($option->value);
        }

        foreach ($data['product_ids'] as $product_id) {
            if (empty($stats[$product_id])) {
                $stats[$product_id] = 0;
            }
            $stats[$product_id] ++;
        }

        $option->value = Json::encode($stats);
        $option->save();
    }
}
