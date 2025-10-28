<?php
return [
    'name' => '<h2 class="text-primary mt-2 me-4"><i class="fa fa-car me-3 "></i>PSIAssit</h2>', // ← subreposição do nome da aplicação
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
];
