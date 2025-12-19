<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\RequestRating;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class RequestController extends ActiveController
{

    public $modelClass = 'common\models\Request';
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
        //$behaviors = parent::behaviors();
        //$behaviors['authenticator'] = [
        //'class' => CustomAuth::class
        //];
        //return $behaviors;
    }

    //test
    public function actionCount(){
        $requestmodel = new $this->modelClass;
        $recs = $requestmodel::find()->all();
        return ['count' => count($recs)];
    }

    #region ------- Request Rating -------


    #endregion

}