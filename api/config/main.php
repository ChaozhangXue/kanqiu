<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'csrfParam' => '_csrf-api',
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
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
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        //修改返回值  如果是gii的话 需要注释这一段
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if (is_string($response->data)) { //gii的是string  正常是数组
                    return;
                } else {
                    $response->data = [
                        'code' => ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) ? 200 : 500,
                        'msg' => $response->statusText,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                    file_put_contents(\Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'xc.log', json_encode($response->data) . PHP_EOL, FILE_APPEND);
                }
            },
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => [
                        'user-info',
                        'watch-list',
                    ]
                ],
                'POST user-info/register' => 'user-info/register',
            ]
        ],
    ],
    'params' => $params,
];
