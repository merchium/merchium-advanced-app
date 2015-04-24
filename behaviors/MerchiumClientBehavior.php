<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Url;
use yii\db\ActiveRecord;

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
            $model = $this->owner;
            $this->client = new \MerchiumClient(
                Yii::$app->params['appKey'],
                Yii::$app->params['clientSecret'],
                $model->domain,
                $model->access_token
            );
        }

        return $this->client;
    }

    public function updateHooks($event)
    {
        return;
        // TODO
        $model = $this->owner;
        $client = $this->getClient();

        $options = $model->getStoreOptionsByName();
        if ($options['enable_snow']) {
            if ($options['enable_snow']->value) {
                //
            } else {
                //
            }
            pd($options['enable_snow']->value);
        }

        $scheme = Yii::$app->request->isSecureConnection ? 'https' : 'http';
        pd(Url::to('@web/js/lib/snowstorm/snowstorm.js', $scheme));
        define('MERCHIUM_DEBUG',1);
        $hooks = $client->getRequest('script_tags');
        pd($hooks);
    }

}
