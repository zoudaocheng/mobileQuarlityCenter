<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
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
        'kang_authority_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.114.121;dbname=kang_authority_db',
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
                'from'=>['root'=>'质量保障部']
            ],
        ],

    ],

    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
