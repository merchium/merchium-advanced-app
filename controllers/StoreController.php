<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Store;
use app\models\StoreOption;
use app\models\search\StoreSearch;
use app\models\forms\StoreOptionsForm;

class StoreController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Store models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Store model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        if ($id) {
            $store = $this->findModel($id);
        } else {
            $store = new Store();
        }

        if ($store->load(Yii::$app->request->post()) && $store->save()) {
            Yii::$app->session->setFlash('success', __('Your changes has been saved successfully.'));
            return $this->redirect(['index']);
        } else {

            $model = new StoreOptionsForm;
            $model->setStore($store);
                    
            $request = Yii::$app->request;
            if ($request->isPost) {
                $model->load($request->post());
                if ($model->saveOptions()) {
                    $store->touch('updated_at');
                    $store->save();
                    Yii::$app->session->setFlash('success', __('Your changes has been saved successfully.'));
                }
                return $this->redirect(['update', 'id' => $store->id]);
            }

            return $this->render('update', [
                'store' => $store,
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Store model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id = null, array $ids = null)
    {
        $ok_message = false;
        
        if (!$id && $ids) { // multiple
            if (Store::deleteAll(['id' => $ids])) {
                $ok_message = __('Items have been deleted successfully.');
            }
        } elseif ($this->findModel($id)->delete()) { // single
            $ok_message = __('Item has been deleted successfully.');
        }

        if ($ok_message) {
            Yii::$app->session->setFlash('success', $ok_message);
            // if ($referrer = Yii::$app->request->referrer) {
            //     return $this->redirect($referrer);
            // }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Store model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Store the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(__('The requested page does not exist.'));
        }
    }

}
