<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\RequestRating;
use Yii;
use yii\rest\ActiveController;

class RatingController extends ActiveController
{
    public $modelClass = 'common\models\RequestRating';
    public $user=null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class
        ];
        return $behaviors;
    }

    public function actionAllratings(){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->all();
        return ['All Ratings' => $recs];
    }

    public function actionRating($id){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->where(['id' => $id])->one();
        return ['request' => $recs];
    }

    public function actionCreaterating(){

        $model = new $this->modelClass;

        $model->id = 0;
        $model->request_id = Yii::$app->request->getBodyParam('request_id');
        $model->title = Yii::$app->request->getBodyParam('title');
        $model->description = Yii::$app->request->getBodyParam('description');
        $model->score = Yii::$app->request->getBodyParam('score');;
        $model->created_at = date('Y-m-d H:i:s');
        $model->created_by = Yii::$app->params['id'];

        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        } else {
            return $model->getErrors();
        }
        throw new \yii\web\BadRequestHttpException("Nenhum dado recebido.");
    }

    public function actionUpdaterating($id)
    {
        $model = ($this->modelClass)::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Registo não encontrado.");
        }

        if ($model!==null) {
            $model->title = Yii::$app->request->getBodyParam('title');
            $model->description = Yii::$app->request->getBodyParam('description');
            $model->score = Yii::$app->request->getBodyParam('score');;
            return $model;
        } else {
            return $model->getErrors();
        }
    }

    public function actionDeleterating($id)
    {
        $model = ($this->modelClass)::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Registo não encontrado.");
        }
        else{
            $model->delete();
            return "Rating deletado com sucesso.";
        }
    }


}