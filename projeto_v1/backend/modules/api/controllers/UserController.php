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
        // Use the User model (or a specific SignupForm model if preferred)
        $model = new \common\models\User();

        // Load data from the request body (important for JSON APIs)
        // The empty string '' tells load() to get data without a form name prefix.
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '')) {

            // 1. Set Password Hash
            $model->setPassword($model->password);

            // 2. Generate Authentication Key (the access_token for the mobile app)
            $model->generateAuthKey();

            // 3. Set Status (e.g., active)
            $model->status = \common\models\User::STATUS_ACTIVE;

            // 4. Validate and Save
            if ($model->save()) {

                // Success: Return the new user's access token and ID
                Yii::$app->response->statusCode = 201; // HTTP 201 Created
                return [
                    'success' => true,
                    'user_id' => $model->id,
                    'access_token' => $model->getAuthKey(), // Mobile app will use this for future requests
                ];

            } else {
                // Failure: Validation errors occurred on save
                // Set the HTTP status code to 422 (Unprocessable Entity)
                Yii::$app->response->statusCode = 422;
                return $model->getErrors();
            }
        }

        // Failure: Data load failed (e.g., missing POST data)
        Yii::$app->response->statusCode = 400; // HTTP 400 Bad Request
        return ['error' => 'Invalid data provided.'];
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
            Yii::$app->response->statusCode = 422; // Unprocessable Entity (Standard for validation errors)
            return $model->getErrors();
        }

    }

    //'POST logout'   => 'logout'
    public function actionLogout(){
        // 1. Get the current authenticated user's Identity object
        $user = Yii::$app->user->identity;

        if ($user) {
            // 2. Invalidate the existing token by generating a new one
            // This makes the previous token (which the client is currently using) invalid.
            $user->generateAuthKey();

            // 3. Save the new token (invalidating the old one in the process)
            if ($user->save(false)) { // Save(false) to skip validation since we only changed auth_key

                // Success: Token invalidated on the server
                return [
                    'success' => true,
                    'message' => 'Token invalidated. User logged out successfully.'
                ];
            }
        }

        // Fallback or if the user was somehow not authenticated (shouldn't happen with proper behaviors)
        Yii::$app->response->statusCode = 401; // HTTP 401 Unauthorized
        return [
            'success' => false,
            'message' => 'User could not be logged out or is not authenticated.'
        ];
    }

//------- Assistances -------

    //'PATCH {id}/cancel'  => 'cancel'
    public function actionCancel($id){
        // Find the resource (e.g., Order, Booking)
        $model = \app\models\Order::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("The requested resource was not found.");
        }

        // Check if the resource can be canceled (e.g., not already completed)
        if ($model->isCancellable()) {

            // Update the status
            $model->status = \app\models\Order::STATUS_CANCELLED;

            if ($model->save(false)) { // Save(false) skips validation for simple status change
                Yii::$app->response->statusCode = 200;
                return [
                    'success' => true,
                    'message' => 'Resource successfully cancelled.'
                ];
            }
        }

        Yii::$app->response->statusCode = 400; // Bad Request or Validation Error
        return ['error' => 'Resource cannot be cancelled in its current state.'];
    }

    //PATCH {id}/status'  => 'status'
    public function actionStatus($id){
        $model = \app\models\Order::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException("The requested resource was not found.");
        }

        // Load the new status value from the request body (e.g., {'status': 'completed'})
        // The empty string '' ensures the data is read without a form name prefix.
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '')) {

            // You should have a validation rule in the model to check if the new status is valid
            if ($model->save()) {
                Yii::$app->response->statusCode = 200;
                return [
                    'success' => true,
                    'message' => 'Resource status updated.',
                    'status' => $model->status
                ];
            } else {
                // Validation errors (e.g., invalid status value)
                Yii::$app->response->statusCode = 422;
                return $model->getErrors();
            }
        }

        Yii::$app->response->statusCode = 400; // Bad Request (No data provided)
        return ['error' => 'No status data provided for update.'];
    }

    //'POST {id}/rating'   => 'rating'
    public function actionRating($id){
        // Assuming you have a separate Rating model
        $rating = new \app\models\Rating();

        // Set the foreign key to the main resource ID
        $rating->resource_id = $id;

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
        $report = new \app\models\Report();

        $report->resource_id = $id;
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
        $reports = \app\models\Report::find()
            ->where(['resource_id' => $id])
            ->with('user') // Eager load the user who submitted the report
            ->all();

        if (empty($reports)) {
            Yii::$app->response->statusCode = 404; // Not Found if no reports exist
            return ['message' => 'No reports found for this resource.'];
        }

        Yii::$app->response->statusCode = 200;
        // Yii will automatically serialize the ActiveRecord objects to JSON
        return $reports;
    }

    //'POST {id}/messages' => 'send-message'
    public function actionSendMessages($id){
        // Assuming you have a separate Message model
        $message = new \app\models\Message();

        $message->resource_id = $id;
        $message->sender_id = Yii::$app->user->id;

        // Load message data (e.g., 'body')
        if ($message->load(Yii::$app->getRequest()->getBodyParams(), '') && $message->save()) {

            Yii::$app->response->statusCode = 201; // HTTP 201 Created
            return [
                'success' => true,
                'message' => 'Message sent.',
                'message_id' => $message->id
            ];

        } else {
            Yii::$app->response->statusCode = 422;
            return $message->getErrors();
        }
    }

    //'GET  {id}/messages' => 'messages'
    public function actionMessages($id){
        // Find all messages related to the resource ID, ordered by time
        $messages = \app\models\Message::find()
            ->where(['resource_id' => $id])
            ->orderBy(['created_at' => SORT_ASC]) // Show chronologically
            ->with('sender') // Eager load the sender
            ->all();

        Yii::$app->response->statusCode = 200;
        // Return the list of messages
        return $messages;
    }

//------- Technicians -------

    //'PUT {id}/availability' => 'set-availability'
    public function actionSetAvailability($id){
        // Find the Technician model
        $technician = \app\models\Technician::findOne($id);

        if (!$technician) {
            throw new \yii\web\NotFoundHttpException("Technician not found.");
        }

        // Load ALL data from the request body into the model (e.g., availability array)
        // NOTE: Availability data is complex; you might use a separate Availability model
        // or a specialized form model for better validation. For simplicity here,
        // we assume the Technician model handles the update.
        if ($technician->load(Yii::$app->getRequest()->getBodyParams(), '') && $technician->save()) {

            Yii::$app->response->statusCode = 200; // HTTP 200 OK (Successful update)
            return [
                'success' => true,
                'message' => "Availability for Technician #$id updated successfully."
            ];

        } else {
            Yii::$app->response->statusCode = 422; // Unprocessable Entity
            return $technician->getErrors();
        }
    }

    //'GET {id}/availability' => 'get-availability'
    public function actionGetAvailability($id){
        // Find the Technician model, and potentially eager-load related availability records
        $technician = \app\models\Technician::find()
            ->where(['id' => $id])
            ->with('availability') // Assuming 'availability' is a relation
            ->one();

        if (!$technician) {
            throw new \yii\web\NotFoundHttpException("Technician not found.");
        }

        Yii::$app->response->statusCode = 200;

        // Return just the availability data from the model relation
        return $technician->availability;
    }

//------- Push Notifications -------

    //'POST register' => 'register-device'
    public function actionRegisterDevice($id){
        // Assume you have a Device model to store tokens
        $device = new \app\models\Device();

        // Load data: device_token, platform (ios/android), and user_id (if authenticated)
        if ($device->load(Yii::$app->getRequest()->getBodyParams(), '')) {

            // Always link the device to the currently authenticated user
            $device->user_id = Yii::$app->user->id;

            // Check if the device token already exists and update it, otherwise save new
            if ($device->save()) {

                Yii::$app->response->statusCode = 201; // HTTP 201 Created
                return [
                    'success' => true,
                    'message' => 'Device successfully registered for push notifications.'
                ];

            } else {
                Yii::$app->response->statusCode = 422;
                return $device->getErrors();
            }
        }

        Yii::$app->response->statusCode = 400;
        return ['error' => 'Missing device token or platform information.'];
    }

//------- Notifications -------

    //'PATCH {id}/read' => 'read'
    public function actionRead($id){
        // Find the Notification model. Ensure it belongs to the current user.
        $notification = \app\models\Notification::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id // Security check
        ]);

        if (!$notification) {
            throw new \yii\web\NotFoundHttpException("Notification not found.");
        }

        // Update the 'read' status
        $notification->is_read = 1;

        if ($notification->save(false)) { // Save(false) to skip validation
            Yii::$app->response->statusCode = 200;
            return [
                'success' => true,
                'message' => 'Notification marked as read.',
                'id' => $id
            ];
        }

        Yii::$app->response->statusCode = 500;
        return ['error' => 'Could not update notification status.'];
    }

//------- Sync Offline -------

    //'GET changes' => 'changes'
    public function actionGetChanges(){
        // 1. Get the last sync timestamp or version ID from the client query params
        $lastSyncTime = Yii::$app->request->get('since');

        // 2. Fetch the required data changes (e.g., all new/updated records)
        $changes = [
            'new_orders' => \app\models\Order::getChangesSince($lastSyncTime),
            'updated_technicians' => \app\models\Technician::getChangesSince($lastSyncTime),
            // ... include other necessary models
        ];

        Yii::$app->response->statusCode = 200;

        // Return all changes categorized by model
        return $changes;
    }

    //'POST batch'  => 'batch'
    public function actionBatch(){
        $transaction = Yii::$app->db->beginTransaction();
        $processedResults = [];
        $hasError = false;

        try {
            // 1. Get the full batch array from the request body
            $batchData = Yii::$app->getRequest()->getBodyParams();

            if (!is_array($batchData)) {
                throw new \Exception('Invalid batch format.');
            }

            // 2. Loop through the batched operations (e.g., creating new records)
            foreach ($batchData as $operation) {

                // Example processing for creating a new offline-collected record:
                if ($operation['type'] === 'create_record') {
                    $model = new \app\models\OfflineRecord();
                    if ($model->load($operation['data'], '') && $model->save()) {
                        $processedResults[] = ['status' => 'success', 'client_id' => $operation['client_id'], 'server_id' => $model->id];
                    } else {
                        $processedResults[] = ['status' => 'error', 'client_id' => $operation['client_id'], 'errors' => $model->getErrors()];
                        $hasError = true;
                    }
                }
                // ... add logic for updates, deletions, etc.
            }

            if ($hasError) {
                $transaction->rollBack();
                Yii::$app->response->statusCode = 422;
            } else {
                $transaction->commit();
                Yii::$app->response->statusCode = 200;
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->response->statusCode = 500;
            return ['error' => 'A critical error occurred during batch processing.'];
        }

        return ['results' => $processedResults, 'success' => !$hasError];
    }
}
