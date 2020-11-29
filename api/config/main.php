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
                        'v1/user-info',
                        'v1/watch-list',
                        'v1/address',
                        'v1/client',
                        'v1/suggestion',
                        'v1/complaint',
                        'v1/bus-repair',
                        'v1/service-station-repair',
                        'v1/message',
                        'v1/bus-order',
                        'v1/package-order',
                        'v1/order',
                        'v1/bus',
                        'v1/driver-order',
                        'v1/bus-line',
                        'v1/bus-station',
                        'v1/bus-repair',
                        'v1/drive-track',
                        'v1/motor',
                        'v1/order',
                        'v1/package',
                        'v1/pay',
                        'v1/public',
                        'v1/service-station',
                        'v1/service-station-account',
                        'v1/service-station-auth',
                        'v1/service-station-repair',
                        'v1/station-allocation',
                        'v1/package-type',
                    ]
                ],
                'POST v1/public/<action:\w+>' => 'v1/public/<action>',
                'POST common/file-transfer' => 'common/file-transfer',
                'POST common/test' => 'common/test',
                'POST v1/public/station-login' => 'v1/public/station-login',
                'POST v1/public/driver-login' => 'v1/public/driver-login',
                'POST v1/public/send-code' => 'v1/public/send-code',

                'POST v1/customer/<action:\w+>' => 'v1/customer/<action>',
                'POST v1/customer/user-identity' => 'v1/customer/user-identity',
                'POST v1/customer/verify-user-identity' => 'v1/customer/verify-user-identity',
                'POST v1/customer/change-avatar' => 'v1/customer/change-avatar',
                'POST v1/customer/change-profile' => 'v1/customer/change-profile',
                'GET v1/customer/profile' => 'v1/customer/profile',
                'POST v1/customer/change-password' => 'v1/customer/change-password',
                'POST v1/customer/station-identity' => 'v1/customer/station-identity',
                'POST v1/customer/verify-station-identity' => 'v1/customer/verify-station-identity',
                'GET v1/customer/bill' => 'v1/customer/bill',
                'GET v1/customer/consume' => 'v1/customer/consume',

                'POST v1/message/read' => 'v1/message/read',

                'POST v1/bus-order/driver-complete' => 'v1/bus-order/driver-complete',
                'POST v1/bus-order/complete' => 'v1/bus-order/complete',
                'POST v1/bus-order/cancel' => 'v1/bus-order/cancel',
                'POST v1/bus-order/reject' => 'v1/bus-order/reject',
                'POST v1/bus-order/receive' => 'v1/bus-order/receive',
                'POST v1/bus-order/pay' => 'v1/bus-order/pay',
                'POST v1/bus-order/get-pay-money' => 'v1/bus-order/get-pay-money',
                'POST v1/bus-order/scan' => 'v1/bus-order/scan',
                'GET v1/bus-order/view' => 'v1/bus-order/view',
                'POST v1/bus-order/start' => 'v1/bus-order/start',
                'GET v1/bus-line/search' => 'v1/bus-line/search',

                'POST v1/driver-order/accept' => 'v1/driver-order/accept',
                'POST v1/driver-order/reject' => 'v1/driver-order/reject',
                'GET v1/driver-order/pending' => 'v1/driver-order/pending',

                'GET v1/pay/notify' => 'v1/pay/notify',
                'POST v1/pay/notify' => 'v1/pay/notify',

                'POST v1/package-order/get-price' => 'v1/package-order/get-price',
                'PUT v1/package-order/batch-update' => 'v1/package-order/batch-update',
                'POST v1/package-order/pickup-scan' => 'v1/package-order/pickup-scan',
                'POST v1/package-order/bind-list' => 'v1/package-order/bind-list',
                'POST v1/package-order/pay' => 'v1/package-order/pay',
                'GET v1/package-order/search-package' => 'v1/package-order/search-package',
                'PUT v1/package-order/reject-package' => 'v1/package-order/reject-package',

                'POST v1/order/generate' => 'v1/order/generate',
                'PUT v1/order/assign' => 'v1/order/assign',
                'POST v1/order/driver-scan' => 'v1/order/driver-scan',
                'POST v1/order/station-scan' => 'v1/order/station-scan',
                'POST v1/order/driver-accept' => 'v1/order/driver-accept',
                'GET v1/order/get-by-receive-id' => 'v1/order/get-by-receive-id',
                'POST v1/order/generate-get-order' => 'v1/order/generate-get-order',
                'POST v1/order/driver-press-start' => 'v1/order/driver-press-start',
                'POST v1/order/arrive-center' => 'v1/order/arrive-center',
                'GET v1/order/pending' => 'v1/order/pending',
                'GET v1/order/get-station-send' => 'v1/order/get-station-send',
                'GET v1/order/get-station-receive' => 'v1/order/get-station-receive',
                'GET v1/order/get-station-complete' => 'v1/order/get-station-complete',

                'GET v1/service-station/bus-line' => 'v1/service-station/bus-line',
                'GET v1/service-station/bus-line-link' => 'v1/service-station/bus-line-link',
                'GET v1/service-station/get-near-station' => 'v1/service-station/get-near-station',
                'GET v1/bus-station/near' => 'v1/bus-station/near',
            ]
        ],
    ],
    'params' => $params,
];
