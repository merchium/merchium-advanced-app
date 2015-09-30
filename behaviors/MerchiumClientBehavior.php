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

    public function sendAdminNotification($type, $message, $title = '', $message_state = '')
    {
        $store = $this->owner;
        $client = $this->getClient();

        $client->createRequest('admin_notifications', [
            'type'          => $type,
            'title'         => $title,
            'message'       => $message,
            'message_state' => $message_state,
        ]);
    }

    public function paymantNotifyRequest($url, $data)
    {
        $delimiter = strpos($url, '?') ? '&' : '?';
        $notify_url = $url . $delimiter . http_build_query($data);
        $notify_url .= '&signature=' . $this->getClient()->calculateSignature($notify_url);
        
        $result = file_get_contents($notify_url);
        
        $decoded = json_decode($result, true);
        if ($decoded) {
            return $decoded;
        }
        return false;
    }

    public function updateHooks($event)
    {
        $store = $this->owner;
        $client = $this->getClient();

        $current_scheme = Yii::$app->request->isSecureConnection ? 'https' : 'http';

        $options = $store->getOptionsByName();

        $scripts = [];

        // Snowfall
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

        // Welcome popup
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

        // "Add to cart" counter
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

        // Payment
        if (isset($options['payment_enable'])) {

            if ($options['payment_enable']->value) {
                
                $data = [
                    'processor' => Yii::$app->params['applicationName'],
                    'redirect_url' => Url::to(['/payment'], $current_scheme),
                ];

                if (!empty($options['payment_processor_id'])) { // Update
                    $client->updateRequest('payment_processors/' . $options['payment_processor_id']->value, $data);
                
                } else { // Create
                    $res = $client->createRequest('payment_processors', $data);
                    if (!empty($res['processor_id'])) {
                        $option_processor = new Option;
                        $option_processor->name = 'payment_processor_id';
                        $option_processor->value = $res['processor_id'];
                        $option_processor->link('store', $store);
                        $option_processor->save();
                    }
                
                }
                
            } else {
                
                if (!empty($options['payment_processor_id'])) {
                    $client->deleteRequest('payment_processors/' . $options['payment_processor_id']->value);
                    $options['payment_processor_id']->delete();
                }

            }

        }

        if ($scripts) {
            $body = 
                '{literal}'
                . '<script type="text/javascript">'
                . '(function(_, $) {'
                . implode(PHP_EOL, $scripts)
                . '}(Tygh, Tygh.$));'
                . '</script>'
                . '{/literal}';
            if (!empty($options['scripts_hook_id'])) {
                // Update
                $client->updateRequest('template_hooks/' . $options['scripts_hook_id']->value, [
                    'body' => $body,
                ]);
            } else {
                // Create
                $res = $client->createRequest('template_hooks', [
                    'hookname' => 'index:scripts',
                    'type' => 'post',
                    'body' => $body,
                ]);
                if (!empty($res['hook_id'])) {
                    $option_hook = new Option;
                    $option_hook->name = 'scripts_hook_id';
                    $option_hook->value = $res['hook_id'];
                    $option_hook->link('store', $store);
                    $option_hook->save();
                }
            }
        } else {
            
            if (!empty($options['scripts_hook_id'])) {
                $client->deleteRequest('template_hooks/' . $options['scripts_hook_id']->value);
                $options['scripts_hook_id']->delete();
            }

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
