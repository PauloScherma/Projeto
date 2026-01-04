<?php

return [
    'id' => 'app-backend-tests',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',

    'components' => [
        'request' => [
            'cookieValidationKey' => 'test',
        ],

        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'BACKENDTESTSESSID',
        ],

        'user' => [
            'identityClass' => common\models\User::class,
            'enableAutoLogin' => false,
            'enableSession' => true,
        ],

        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=dbprojeto_v1_test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],

        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],

        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
