<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Meu Novo Nome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',],
        ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'db' => 'db',
        ],
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
                //UserController
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user', 'pluralize' => false,
                    'extraPatterns'=>[
                        'GET count' => 'count',
                        'POST register' => 'register',
                        'POST login'    => 'login',
                        'POST logout'   => 'logout',
                    ],
                ],
                //RequestController
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/request', 'pluralize' => false,
                    'extraPatterns'=>[
                        'GET count' => 'count',
                        'GET requests/{id}' => 'requests',
                        'GET request/{id}' => 'request',
                        'POST create' => 'create',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
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

