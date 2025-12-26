<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Profile;
use Yii;
use yii\rest\ActiveController;

class ProfileController extends ActiveController
{
    public $modelClass = 'common\models\Profile';
    public $user = null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class
        ];
        return $behaviors;
    }

    public function actionCount()
    {
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionProfile($id)
    {
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->where(['id' => $id])->one();
        return ['request' => $recs];
    }
    public function actionCreateprofile()
    {
        $model = new $this->modelClass;

        $model->id = 0;
        $model->user_id = Yii::$app->params['id'];
        $model->first_name = Yii::$app->request->getBodyParam('first_name');
        $model->last_name = Yii::$app->request->getBodyParam('last_name');
        $model->phone = Yii::$app->request->getBodyParam('phone');
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        } else {
            return $model->getErrors();
        }
        throw new \yii\web\BadRequestHttpException("Nenhum dado recebido.");
    }
    public function actionUpdateprofile($id)
    {
        $model = ($this->modelClass)::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Registo não encontrado.");
        }

        if ($model!==null) {
            $model->first_name = Yii::$app->request->getBodyParam('first_name');
            $model->last_name = Yii::$app->request->getBodyParam('last_name');
            $model->phone = Yii::$app->request->getBodyParam('phone');
            $model->save();

            return $model;
        } else {
            return $model->getErrors();
        }
    }
    public function actionDeleteprofile($id)
    {
        $model = ($this->modelClass)::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Registo não encontrado.");
        }
        else{
            $model->delete();
            return "Profile deletado com sucesso.";
        }
    }
}