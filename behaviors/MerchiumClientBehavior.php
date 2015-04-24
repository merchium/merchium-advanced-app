<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use app\models\StoreOption;

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

        $options = $store->getStoreOptionsByName();

        if (isset($options['snowfall_enable'])) {

            if (!empty($options['snowfall_script_tag_hash'])) {
                $hash_option = $options['snowfall_script_tag_hash'];
            } else {
                $hash_option = new StoreOption;
                $hash_option->name = 'snowfall_script_tag_hash';
                $hash_option->link('store', $store);
            }

            // Enable
            if ($options['snowfall_enable']->value) {

                // Script tag
                if ($hash_option->value) {
                    // remove previos if exists
                    $client->deleteRequest('script_tags/' . $hash_option->value);
                }
                $res = $client->createRequest('script_tags', [
                    'src' => Url::to('@web/js/lib/snowfall/snowfall.jquery.js', $current_scheme),
                ]);
                if (!empty($res['src_hash'])) {
                    $hash_option->value = $res['src_hash'];
                    $hash_option->save();
                }

                // Template hook
                $client->createRequest('template_hooks', [
                    'hookname' => 'index:scripts',
                    'type' => 'post',
                    'body' => '
                        {literal}
                        <script type="text/javascript">
                        (function(_, $) {
                            $(function(){
                                setTimeout(function(){
                                    $(document).snowfall({flakeCount: 100, maxSpeed: 5, maxSize: 4, shadow: true});
                                }, 500);
                            });
                        }(Tygh, Tygh.$));
                        </script>
                        {/literal}
                    ',
                ]);

            // Disable
            } else {
                
                // Script tag
                $client->deleteRequest('script_tags/' . $hash_option->value);
                $hash_option->delete();

                // Template hook
                $client->deleteRequest('template_hooks/index:scripts');

            }
        }

    }

}
