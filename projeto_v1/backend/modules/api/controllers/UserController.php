<?php

namespace backend\modules\api\controllers;

use common\models\User;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\rest\Controller;
use function ActiveRecord\all;

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

        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            //'class' => QueryParamAuth::className(),
            //'except' => ['index', 'view'], //Excluir aos GETs
            //'auth' => [$this,'authintercept'],
            'class' => \CustomAuth::className()

        ];
        return $behaviors;

    }

    public function authintercept($username, $password){
        $user = \common\models\User::findByUsername($username);
         if ($user && $user->validatePassword($password))
         {
             $this->user=$user; //Guardar user autenticado
             return $user;
         }
         throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }

                    //Custon functions for Api endpoints

    public function actionCount(){

        $usermodel = new $this->modelClass;
        $recs = $usermodel::find()->all();
        return ['count' => count($recs)];
    }

//------- AUTH -------

    //'POST register' => 'register'
    public function actionRegister(){

        $model = new \common\models\User();


        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '')) {

            $model->setPassword($model->password);

            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->status = User::STATUS_ACTIVE;
            $user->roleName = "cliente";


            // 3. Set Status (e.g., active)
            $model->status = \common\models\User::STATUS_ACTIVE;

            // 4. Validate and Save
            if ($model->save()) {

                $auth = Yii::$app->authManager;
                $roleName = 'cliente';
                $role = $auth->getRole($roleName);
                $auth->assign($role, $user->id);

                Yii::$app->response->statusCode = 201;

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

    //'POST login'    => 'login' WORKING
    public function actionLogin(){
        $username = Yii::$app->getRequest()->getBodyParam('username');
        $password = Yii::$app->getRequest()->getBodyParam('password');

        $user = User::findOne(['username' => $username]);

        if (!$user || !$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 401; // Unauthorized
            return [
                'status' => 'error',
                'message' => 'Invalid username or password'
            ];
        }

        $token = Yii::$app->security->generateRandomString();
        $expirationTime = time() + (3600); // Token expires in 1 hour

        $user->access_token = $token;
        $user->token_expires = $expirationTime;

        if (!$user->save()) {
            Yii::$app->response->statusCode = 500;
            Yii::error("Failed to save user token after login: " . json_encode($user->getErrors()));
            return [
                'status' => 'error',
                'message' => 'Internal server error during token issuance.'
            ];
        }

        return [
            'status' => 'success',
            'access_token' => $token,
            'token_expires_at' => $expirationTime,
        ];
    }


    //'POST logout'   => 'logout' WORKING
    public function actionLogout()
    {
        $accessToken = Yii::$app->request->headers->get('Authorization');

        if (!$accessToken) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Access token missing'];
        }

        $user = User::findOne(['accessToken' => $accessToken]);

        if (!$user) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Invalid token'];
        }

        $user->accessToken = null;
        $user->token_expires = null;

        if ($user->save(false)) {
            Yii::$app->response->statusCode = 200;
            return ['status' => 'success'];
        }

        Yii::$app->response->statusCode = 500;
        return ['status' => 'error', 'message' => 'Failed to logout'];
    }*/

//------- Assistances -------

    //'Put {id}/cancel'  => 'cancel'
    /*public function actionSetCancel($id)
    {
        // Find the request
        $request = Request::findOne($id);

        if (!$request) {
            throw new \yii\web\NotFoundHttpException("The requested resource was not found.");
        }

        // Check if the request can be cancelled
        if ($request->isCancellable()) {

            // Update the status
            $request->status = \common\models\Request::STATUS_CANCELLED;

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
    }*/

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

    //'POST {id}/messages' => 'send-message'
   /* Not Going to Implement
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
    */
    //'GET  {id}/messages' => 'messages'
   /*
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
   */

//*/------- Technicians -------

    //'PUT {id}/availability' => 'set-availability'
   /* public function actionSetAvailability($userid)
    {
        $profileTech = \common\models\Profile::findOne($userid);

        if (!$profileTech) {
            throw new \yii\web\NotFoundHttpException("Technician not found.");
        }

        // This reads "true" or "false" from Android
        $value = Yii::$app->request->post('availability');

        // Convert the incoming string to actual boolean
        $profileTech->availability = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($profileTech->save()) {
            return [
                'success' => true,
                'availability' => (bool) $profileTech->availability
            ];
        }

        return [
            'success' => false,
            'errors' => $profileTech->errors
        ];
    }


    //'GET {id}/availability' => 'get-availability'
    public function actionGetAvailability($id)
    {
        $profile = \common\models\Profile::findOne($id);

        if (!$profile) {
            throw new \yii\web\NotFoundHttpException("Technician not found.");
        }

        return [
            'availability' => (bool) $profile->availability
        ];
    }*/


//------- Push Notifications ------- // not going to implement not necessary

    //'POST register' => 'register-device'
   /*
    public function actionRegisterDevice($id){
        // Assume you have a Device model to store tokens
        $device = new \app\models\();

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
*/
//------- Notifications -------

    //'PATCH {id}/read' => 'read' //Not going to implement
  /*
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
*/
//------- Sync Offline -------

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
    }

    //'POST batch'  => 'batch'
    /* // NOT NEEDED
    public function actionBatch(){
        // Either everything succeeds, or everything fails—no half-saved data.
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
    */
}
