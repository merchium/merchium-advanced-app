<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
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

        $scripts = [];

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
                
                // Script tag
                $client->deleteRequest('script_tags/' . $hash_option->value);
                $hash_option->delete();

            }
        }

        if (isset($options['welcome_popup_enable'])) {

            if ($options['welcome_popup_enable']->value) {
                $welcome_popup_data = [
                    'title' => Html::encode($options['welcome_popup_title']->value),
                    'content' => Html::encode($options['welcome_popup_content']->value),
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

}
