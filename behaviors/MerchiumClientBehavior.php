<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use app\models\Option;

class MerchiumClientBehavior extends Behavior
{
    protected $client;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateHooks',
        ];
    }

    public function getClient()
    {
        if (!$this->client) {
            $store = $this->owner;
            $this->client = new \MerchiumClient(
                Yii::$app->params['appKey'],
                Yii::$app->params['clientSecret'],
                $store->domain,
                $store->access_token
            );
        }

        return $this->client;
    }

    public function updateHooks($event)
    {
        $store = $this->owner;
        $client = $this->getClient();

        $current_scheme = Yii::$app->request->isSecureConnection ? 'https' : 'http';

        $options = $store->getOptionsByName();

        $scripts = [];

        if (isset($options['snowfall_enable'])) {

            if (!empty($options['snowfall_script_tag_hash'])) {
                $option_hash = $options['snowfall_script_tag_hash'];
                if ($option_hash->value) { // remove previos if exists
                    $client->deleteRequest('script_tags/' . $option_hash->value);
                }
            } else {
                $option_hash = new Option;
                $option_hash->name = 'snowfall_script_tag_hash';
                $option_hash->link('store', $store);
            }

            // Enable
            if ($options['snowfall_enable']->value) {

                // Script tag
                $res = $client->createRequest('script_tags', [
                    'src' => Url::to('@web/js/lib/snowfall/snowfall.jquery.js', $current_scheme),
                ]);
                if (!empty($res['src_hash'])) {
                    $option_hash->value = $res['src_hash'];
                    $option_hash->save();
                }

                // Template hook
                $scripts[] = '
                    var snowfallLoad = function(){
                        if ($.snowfall) {
                            $(document).snowfall({flakeCount: 100, maxSpeed: 5, maxSize: 4, shadow: true});
                        } else {
                            setTimeout(function(){
                                snowfallLoad();
                            }, 300);
                        }
                    };
                    snowfallLoad();
                ';

            // Disable
            } else {
                
                $option_hash->delete();

            }
        }

        if (isset($options['welcome_popup_enable'])) {

            if ($options['welcome_popup_enable']->value) {
                $welcome_popup_data = [
                    'title' => Html::encode($options['welcome_popup_title']->value),
                    'content' => nl2br(Html::encode($options['welcome_popup_content']->value)),
                ];
                $welcome_popup_data_json = Json::encode($welcome_popup_data);

                $scripts[] = "
                    $(function(){
                        var welcome_popup_data = {$welcome_popup_data_json};
                        if (!$.cookie.get('welcome_popup_displayed')) {
                            $.cookie.set('welcome_popup_displayed', true);
                            $.ceNotification('show', {
                                type: 'I',
                                title: welcome_popup_data.title,
                                message: '<div style=\"margin: 0 20px 0 20px;\">' + welcome_popup_data.content + '</div>',
                                message_state: 'S'
                            }, 'popupmodal');
                        }
                    });
                ";
            }

        }

        if (isset($options['add_to_cart_counter_enable'])) {

            if (!empty($options['add_to_cart_counter_webhook_id'])) {
                $option_webhook = $options['add_to_cart_counter_webhook_id'];
                if ($option_webhook->value) { // remove previos if exists
                    $client->deleteRequest('webhooks/' . $option_webhook->value);
                }
            } else {
                $option_webhook = new Option;
                $option_webhook->name = 'add_to_cart_counter_webhook_id';
                $option_webhook->link('store', $store);
            }

            if ($options['add_to_cart_counter_enable']->value) {
                
                $res = $client->createRequest('webhooks', [
                    'event' => 'add_to_cart',
                    'url' => Url::to(['/webhook', 'store_id' => $store->id], $current_scheme),
                ]);
                if (!empty($res['webhook_id'])) {
                    $option_webhook->value = $res['webhook_id'];
                    $option_webhook->save();
                }

            } else {
                
                $option_webhook->delete();

            }

        }

        if ($scripts) {
            $client->createRequest('template_hooks', [
                'hookname' => 'index:scripts',
                'type' => 'post',
                'body' => '
                    {literal}
                    <script type="text/javascript">
                    (function(_, $) {
                    ' . implode(PHP_EOL, $scripts) . '
                    }(Tygh, Tygh.$));
                    </script>
                    {/literal}',
            ]);
        } else {
            $client->deleteRequest('template_hooks/index:scripts');
        }

    }

    public function getAddToCartStats()
    {
        $data = [];

        $store = $this->owner;
        $option = Option::findOne(['store_id' => $store->id, 'name' => 'add_to_cart_counter_statistics']);
        if ($option) {
            $stats = Json::decode($option->value);
            if ($stats) {
                arsort($stats);
                $data = $stats;
                $client = $this->getClient();
                $product_ids = array_keys($stats);
                $result = $client->getRequest('products', ['pid' => $product_ids]);
                if (!empty($result['products'])) {
                    $products = $result['products'];
                    foreach ($products as $product) {
                        $product['added_to_cart'] = $stats[$product['product_id']];
                        $data[$product['product_id']] = $product;
                    }
                }
            }
        }

        return $data;
    }

}
