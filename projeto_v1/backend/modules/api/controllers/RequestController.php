<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Request;
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
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class
        ];
        return $behaviors;
    }

    //test
    public function actionCount(){

        //$usermodel = new $this->modelClass;
        //$recs = $usermodel::find()->all();
        return Yii::$app->params['id'];
    }

    #region ------- Request -------

    public function actionRequests($id){
        $usermodel = new $this->modelClass;
        $recs = $usermodel::find()->where(['customer_id' => $id])->all();
        return ['recs' => $recs];
    }

    public function actionRequest($id){
        $usermodel = new $this->modelClass;
        $recs = $usermodel::find()->where(['id' => $id])->one();
        return ['recs' => $recs];
    }
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'message' => 'Usuário não autenticado.'
            ];
        }

        $requestModel = new $this->modelClass;
        $requestModel->customer_id = 53;
        $requestModel->tittle = \Yii::$app->request->post('tittle');
        $requestModel->description = \Yii::$app->request->post('description');
        $requestModel->status = "new";
        $requestModel->created_at = date('Y-m-d H:i:s');
        $requestModel->save();
        return "Sucess";
    }

    public function actionPutRequests($id){

    }
    public function actionDeleteRequests($id){

    }
    #endregion

    /*#region------- Assistances -------

    //'Put {id}/cancel'  => 'cancel'
    public function actionSetCancel($id)
    {
        $request = Request::findOne($id);

        if (!$request) {
            Yii::$app->response->statusCode = 404;
            return [
                'message' => 'Request not found.'
            ];
        }
        else {

            // Update the status
            $request->status = Request::STATUS_CANCELLED;

            // Save without validation (safe if only updating 1 field)
            if ($request->save(false)) {
                Yii::$app->response->statusCode = 200;

                return [
                    'success' => true,
                    'message' => 'Resource successfully cancelled.'
                ];
            }

            Yii::$app->response->statusCode = 500;
            return [
                'error' => 'Failed to save cancellation state.'
            ];
        }

        Yii::$app->response->statusCode = 400;
        return [
            'error' => 'Resource cannot be cancelled in its current state.'
        ];
    }

    //'GET' {id}/status'  => 'status'
    public function actionGetStatus($id)
    {
        $request = \common\models\Request::findOne($id);

        if (!$request) {
            throw new \yii\web\NotFoundHttpException("Request not found.");
        }

        return [
            'status' => $request->status
        ];
    }

    //PATCH {id}/status'  => 'status'
    public function actionStatus($id)
    {
        $request = \common\models\Request::findOne($id);

        if (!$request) {
            throw new \yii\web\NotFoundHttpException("Request not found.");
        }

        // Read the incoming value from Android
        $status = Yii::$app->request->post('status');

        // Allowed ENUM values
        $allowedStatuses = [
            'new',
            'assigned',
            'in_progress',
            'waiting_parts',
            'completed',
            'canceled'
        ];

        // Validate ENUM
        if (!in_array($status, $allowedStatuses)) {
            Yii::$app->response->statusCode = 422; // Unprocessable Entity
            return [
                'success' => false,
                'error' => "Invalid status value. Must be one of: " . implode(', ', $allowedStatuses)
            ];
        }

        // Assign the enum value
        $request->status = $status;

        if ($request->save()) {
            return [
                'success' => true,
                'status' => $request->status
            ];
        }

        return [
            'success' => false,
            'errors' => $request->errors
        ];
    }

    //'POST {id}/rating'   => 'rating'
    public function actionRating($id){
        // Assuming you have a separate Rating model
        $rating = new \app\models\Request();

        // Set the foreign key to the main resource ID
        $rating->request_id = $id;

        // Set the user ID who is giving the rating
        $rating->user_id = Yii::$app->user->id;

        // Load data (e.g., score, comment) from the request body
        if ($rating->load(Yii::$app->getRequest()->getBodyParams(), '') && $rating->save()) {

            Yii::$app->response->statusCode = 201; // HTTP 201 Created
            return [
                'success' => true,
                'message' => 'Rating submitted successfully.',
                'rating_id' => $rating->id
            ];

        } else {
            Yii::$app->response->statusCode = 422;
            return $rating->getErrors();
        }
    }

    //'POST {id}/reports'  => 'create-report'
    public function actionCreateReports($id){
        // Assuming you have a separate Report model
        $report = new \app\models\Request();

        $report->request_id = $id;
        $report->user_id = Yii::$app->user->id;

        // Load data (e.g., reason, details)
        if ($report->load(Yii::$app->getRequest()->getBodyParams(), '') && $report->save()) {

            Yii::$app->response->statusCode = 201; // HTTP 201 Created
            return [
                'success' => true,
                'message' => 'Report submitted successfully.',
                'report_id' => $report->id
            ];

        } else {
            Yii::$app->response->statusCode = 422;
            return $report->getErrors();
        }
    }

    //'GET  {id}/reports'  => 'list-reports'
    public function actionListReports($id){
        // Find all reports related to the resource ID
        $reports = \common\models\Request::find($id)
            ->where(['request_id' => $id])
            ->all();

        if (empty($reports)) {
            Yii::$app->response->statusCode = 404; // Not Found if no reports exist
            return ['message' => 'No reports found for this resource.'];
        }

        Yii::$app->response->statusCode = 200;
        // Yii will automatically serialize the ActiveRecord objects to JSON
        return $reports;
    }

    #endregion*/

    /*------- Sync Offline -------

    //'GET changes' => 'changes'
    public function actionGetChanges(){
        // 1. Get the last sync timestamp or version ID from the client query params
        $lastSyncTime = Yii::$app->request->get('since');

        // 2. Fetch the required data changes
        $changes = [
            // Add more if needed
            'request' => \common\models\Request::getChangesSince($lastSyncTime),
            'profile' => \common\models\Profile::getChangesSince($lastSyncTime),
            'user' => \common\models\User::getChangesSince($lastSyncTime),
        ];

        Yii::$app->response->statusCode = 200;

        // Return all changes categorized by model
        return $changes;
    }*/
}