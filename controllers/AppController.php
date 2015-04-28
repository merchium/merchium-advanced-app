<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;
use yii\web\NotFoundHttpException;
use app\models\Store;
use app\models\forms\OptionsForm;

class AppController extends Controller
{
    public function init()
    {
        parent::init();

        Yii::$app->session->open();
    }

    public function actionIndex($shop_domain = '')
    {
        $session = Yii::$app->session;

        if ($shop_domain) {
            if ($store = Store::findOne(['domain' => $shop_domain])) {
                $client = $store->getClient();
                if ($client->validateSignature(Yii::$app->request->get())) {
                    $session->set('shop_domain', $shop_domain);
                    return $this->redirect(['index']);
                } else {
                    throw new UnauthorizedHttpException(__('Error validate signature'));
                }
            } else {
                throw new NotFoundHttpException(__('Shop not found'));
            }
        }

        if (
            $session->has('shop_domain')
            && $store = Store::findOne(['domain' => $session->get('shop_domain')])
        ) {
            
            $model = new OptionsForm;
            $model->setStore($store);
        
            $request = Yii::$app->request;
            if ($request->isPost) {
                $model->load($request->post());
                if ($model->saveOptions()) {
                    $store->touch('updated_at');
                    $store->save();
                    Yii::$app->session->setFlash('success', __('Your changes has been saved successfully.'));
                }
                return $this->redirect(['index']);
            }

            $this->layout = 'widget';
            
            return $this->render('index', [
                'store' => $store,
                'model' => $model,
            ]);

        } else {
            throw new NotFoundHttpException(__('Shop not found'));
        }
    }

    public function actionInstall($code, $shop_domain)
    {
        $store = Store::findOne(['domain' => $shop_domain]);
        if (!$store) {
            $store = new Store;
            $store->domain = $shop_domain;
        }

        $client = $store->getClient();
        if (!$client->validateSignature(Yii::$app->request->get())) {
            throw new UnauthorizedHttpException(__('Error validate signature'));
        }

        $store->access_token = $client->requestAccessToken($code);
        $store->save();

        Yii::$app->session->set('shop_domain', $shop_domain);

        return $this->redirect(['index']);
    }

}
