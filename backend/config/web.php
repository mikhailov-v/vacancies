<?php

use yii\filters\Cors;

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
            'cookieValidationKey' => 'M2dBKQ4nM_lxoFgI2Vz0LkoTYGnHf8Zk',
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
            'class' => yii\web\ErrorHandler::class,
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false, // ← важно, иначе несуществующие урлы не передадутся в Yii
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'vacancy',
                    'pluralize' => false,
                ],
            ],
        ],

    ],
    'params' => $params,

    'as corsFilter' => [
        'class' => Cors::class,
        'cors' => [
            'Origin' => [sprintf('%s:%s', getenv('FRONTEND_HOST') ?: 'http://localhost', getenv('FRONTEND_PORT') ?: '3000')],
            'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Allow-Headers' => ['*'],
            'Access-Control-Expose-Headers' => [
                'X-Pagination-Total-Count',
                'X-Pagination-Page-Count',
                'X-Pagination-Current-Page',
                'X-Pagination-Per-Page'
            ],
        ],
    ],
    'as contentNegotiator' => [
        'class' => yii\filters\ContentNegotiator::class,
        'formats' => [
            'application/json' => yii\web\Response::FORMAT_JSON,
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        //'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        //'allowedIPs' => ['*'],
    ];
}

return $config;
