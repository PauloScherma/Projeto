<?php

namespace backend\modules\api;

/**
 * api module definition class
 */
class ModuleAPI extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init(){

        parent::init();
        \Yii::$app->user->enableSession = false;
    }
    //exemplo
    /*public function actionNomes()
    {
        $pratosmodel = new $this->modelClass;
        $recs = $pratosmodel::find()->select(['nome'])->all();
        return $recs;
    }*/

    // custom initialization code goes here
}
