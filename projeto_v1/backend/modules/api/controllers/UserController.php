<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use function ActiveRecord\all;
use common\models\LoginForm;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{

   public $modelClass = 'common\models\User';
   public $user=null;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function behavior()
    {
        // For testing trun auth behavior off
        // Para grantir que nao exixtem problemas com os request da api
        // Se a auth tiver ligada e der problema nao se sabe se o problema é da api ou dos requests
    /*
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except' => ['index', 'view'], //Excluir aos GETs
            'auth' => [$this,'authintercept'],
            //'class' => \CustomAuth::className()

        ];
        return $behaviors;
    */
    }

    /*public function authintercept($username, $password){
        $user = \common\models\User::findByUsername($username);
         if ($user && $user->validatePassword($password))
         {
             $this->user=$user; //Guardar user autenticado
             return $user;
         }
         throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }*/

                    //Custon functions for Api endpoints

    public function actionCount(){

        $usermodel = new $this->modelClass;
        $recs = $usermodel::find()->all();
        return ['count' => count($recs)];
    }

//------- AUTH -------

    //'POST register' => 'register'
    public function actionRegister(){

    }

    //'POST login'    => 'login'
    public function actionLogin(){

        $model = new LoginForm();

        // Load POST data from the request body (important for JSON APIs)
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {

            // Success: Return an access token (AuthKey) or user data
            // NOTE: Ensure your User model implements getAuthKey() from IdentityInterface
            return [
                'success' => true,
                'access_token' => Yii::$app->user->identity->getAuthKey(),
                'user_id' => Yii::$app->user->id,
            ];

        } else {
            // Failure: Return the validation errors
            Yii::$app->response->statusCode = 403; // Unprocessable Entity (Standard for validation errors)
            return $model->getErrors();
        }

    }

    //'POST logout'   => 'logout'
    public function actionLogout(){

    }

//------- Assistances -------
/*
    //'PATCH {id}/cancel'  => 'cancel'
    public function actionCancel($id){

    }

    //PATCH {id}/status'  => 'status'
    public function actionStatus($id){

    }

    //'POST {id}/rating'   => 'rating'
    public function actionRating($id){

    }

    //'POST {id}/reports'  => 'create-report'
    public function actionCreateReports($id){

    }

    //'GET  {id}/reports'  => 'list-reports'
    public function actionListReports($id){

    }

    //'POST {id}/messages' => 'send-message'
    public function actionSendMessages($id){

    }

    //'GET  {id}/messages' => 'messages'
    public function actionMessages($id){

    }

//------- Technicians -------

    //'PUT {id}/availability' => 'set-availability'
    public function actionSetAvailability($id){

    }

    //'GET {id}/availability' => 'get-availability'
    public function actionGetAvailability($id){

    }

//------- Push Notifications -------

    //'POST register' => 'register-device'
    public function actionRegisterDevice($id){

    }

//------- Notifications -------

    //'PATCH {id}/read' => 'read'
    public function actionRead($id){

    }

//------- Sync Offline -------

    //'GET changes' => 'changes'
    public function actionGetChanges(){

    }

    //'POST batch'  => 'batch'
    public function actionBatch(){

    }
*/
}
