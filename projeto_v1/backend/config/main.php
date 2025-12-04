<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Meu Novo Nome', // ← subreposição do nome da aplicação
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',],
        ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // <--- DEVE SER ISTO
            'db' => 'db',
        ],

        //'view' => [],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user', 'pluralize' => false,
                    'extraPatterns'=>[

                        //------- AUTH --------------
                        'POST register' => 'register',
                        'POST login'    => 'login',
                        'POST logout'   => 'logout',

                        //------- ASSISTANCES -------

                        'PUT {id}/delete'  => 'delete',
                        'PUT {id}/setStatus'  => 'setStatus',
                        'GET {id}/getStatus'  => 'getStatus',
                        'POST {id}/rating'   => 'rating',
                        'POST {id}/reports'  => 'createReport',
                        'GET  {id}/reports'  => 'listReports',
                        'POST {id}/messages' => 'sendMessage',
                        'GET  {id}/messages' => 'messages',

                        // ------ TECHNICIANS -------
                        'PUT {id}/availability' => 'set-availability',
                        'GET {id}/availability' => 'get-availability',*/

                        //------ SYNC OFFLINE --------
                        'GET changes' => 'changes',
                        'POST batch'  => 'batch',
                    ],
                ],
            ],
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/projeto/projeto_v1/frontend/web',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];

