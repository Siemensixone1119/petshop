<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'w90Ueg8ZdXck1bjFJHiDox75tAhSJeUt',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // Auth
                'POST auth/register' => 'auth/register',
                'POST auth/login' => 'auth/login',
                'POST auth/logout' => 'auth/logout',

                // Categories
                'GET categories' => 'categorie/index',
                'GET categories/<id:\d+>' => 'categorie/view',
                'POST categories' => 'categorie/create',
                'PUT,PATCH categories/<id:\d+>' => 'categorie/update',
                'DELETE categories/<id:\d+>' => 'categorie/delete',

                // Products
                'GET products' => 'product/index',
                'GET categories/<categoryId:\d+>/products' => 'product/index',
                'GET products/<id:\d+>' => 'product/view',
                'POST categories/<categoryId:\d+>/products' => 'product/create',
                'PUT,PATCH categories/<categoryId:\d+>/products/<id:\d+>' => 'product/update',
                'DELETE categories/<categoryId:\d+>/products/<id:\d+>' => 'product/delete',

                // Cart
                'GET cart' => 'cart/index',
                'POST cart/items/<productId:\d+>' => 'cart/add',
                'PUT,PATCH cart/items/<itemId:\d+>' => 'cart/update',
                'DELETE cart/items/<itemId:\d+>' => 'cart/delete',
                'DELETE cart' => 'cart/clear',

                // Orders
                'GET orders' => 'order/index',
                'GET orders/<id:\d+>' => 'order/view',
                'POST orders' => 'order/create',
                'DELETE orders/<id:\d+>' => 'order/cancel',
                'PATCH orders/<id:\d+>/status' => 'order/update-status',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
