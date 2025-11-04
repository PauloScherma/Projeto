<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
   public $user=null;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public $modelClass = 'common\models\User';

    public function behavior()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => ['index', 'view'], //Excluir aos GETs
            'auth' => [$this,'authintercept'],
        ];
        return $behaviors;
    }
    public function authintercept($username, $password)
    {
        $user = \common\models\User::findByUsername($username);
         if ($user && $user->validatePassword($password))
         {
             $this->user=$user; //Guardar user autenticado
             return $user;
         }
         throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'index') {
            throw new \yii\web\ForbiddenHttpException('Acesso proibido para pedidos "Get All"!');
        }
    }

    public function checkDelete($action, $model = null, $params = [])
    {
        if($this->user) {
            if($this->user->id == 2) {
                if($action==="delete") {
                    throw new \yii\web\ForbiddenHttpException('Proibido');
                }
            }
        }
    }
}
