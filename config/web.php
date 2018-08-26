<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'PRC',
    'language' => 'zh-CN',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '$2y$13$HXZsHEHruuaPRe6I/6hkJOjktSUq5WjElz6zms/kangmqc',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'on afterLogin' => function($event){
                $user = $event->identity;
                $user->updated_at = time();
                $user->save();
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qiye.163.com',
                'username' => 'admin@admin.com',
                'password' => 'root',
                'port' => '994',
                'encryption' => 'ssl',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['admin'=>'测试组']
            ],
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
        //权限配置
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        ],
        //替换资源包
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => ['scripts/jquery.min.js']
                ],
            ],
        ],
        'schema' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.165.124.246;dbname=information_schema',
            'username' => 'kang_dev',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=kang_mqc_db',
            'username' => 'root',
            'password' => 'kangroot',
            'charset' => 'utf8',
        ],
        'kang_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql-slave04.kangcorp.com;dbname=kang_db',
            'username' => 'user_readonly',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'kang_car_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql-slave04.kangcorp.com;dbname=kang_car_db',
            'username' => 'user_readonly',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'kang_authority_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.114.121;dbname=kang_authority_db',
            'username' => 'user_readonly',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'kang_finance_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.114.121;dbname=kang_finance_db',
            'username' => 'user_readonly',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'kang_log_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.114.121;dbname=kang_log_db',
            'username' => 'user_readonly',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'kang_market_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.114.121;dbname=kang_market_db',
            'username' => 'user_readonly',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
