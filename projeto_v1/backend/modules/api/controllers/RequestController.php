<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Request;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class RequestController extends ActiveController
{
    public $modelClass = 'common\models\Request';
    public $user=null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class
        ];
        return $behaviors;
    }

    public function actionAllrequests(){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->all();
        return ['All Requests' => $recs];
    }

    public function actionRequests($id){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->where(['customer_id' => $id])->all();
        return ['requests' => $recs];
    }

    public function actionRequest($id){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->where(['id' => $id])->one();
        return ['request' => $recs];
    }

    public function actionCreaterequest()
    {
        $model = new $this->modelClass;

        $model->id = 0;
        $model->customer_id = Yii::$app->params['id'];
        $model->title = Yii::$app->request->getBodyParam('title');
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        } else {
            return $model->getErrors();
        }
        throw new \yii\web\BadRequestHttpException("Nenhum dado recebido.");
    }

    public function actionUpdaterequest($id){

        $model = ($this->modelClass)::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Registo não encontrado.");
        }

        if ($model!==null) {
            $model->title = Yii::$app->request->getBodyParam('title');
            $model->description = Yii::$app->request->getBodyParam('description');
            $model->priority = Yii::$app->request->getBodyParam('priority');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->status = Yii::$app->request->getBodyParam('status');
            $model->save();

            return $model;
        } else {
            return $model->getErrors();
        }
    }

    public function actionDeleterequest($id){
        $model = ($this->modelClass)::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Registo não encontrado.");
        }
        else{
            $model->deleteRequest();
            return "Request deletado com sucesso.";
        }
    }

    public function actionHistory($id){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->where(['customer_id' => $id])->andWhere(['in', 'status', ['canceled', 'completed']])->all();
        return ['requests' => $recs];
    }
}