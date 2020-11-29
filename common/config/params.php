<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'jpush' => [
        'app_key' => '74597e1e02800c9ccf2adf27',
        'market_secret' => 'f6a793a3f30b72bcd3da5545'
    ],
    'identity_verify_url' => 'https://way.jd.com/yingyan/idcard?appkey=4b1476d9f33f8ccf2738f89cb429830f',
    'verify_code_expire' => '90',
    'encrypt' => [
        'key' => '#XinChangQRCODE#', //密钥
        'iv' => 'XC12345678901234',  //偏移量
        'mode' => 'AES-128-CBC',
    ],
    'refund' => false,//退款功能
    'wechat' => [
        'app_id' => 'wxd4886c7ba623dcae',//小程序ID
        'app_secret' => 'faa3405147a5708e4c5086799e1bcbeb',//小程序密钥
        'mch_id' => '1555283351',
        'pay_key' => 'cac4afebc08d994c0123c75d3961f493',
    ],
    'sms' => [
        'AccessKeyID' => 'LTAI4FbeCJFVnQ8UZDWDLzzH',
        'AccessKeySecret' => 'qEQYzFHMetcpR4ac4eFj6rn3EMkhVz',
        'SignName' => '新昌交运'
    ],
    'map' => [
        'app_key' => '2175f2648b6f5b25eb3bdb0b2710b977'
    ],
    'sms_type_list' => [
        1 => [
            'name' => '身份验证验证码',
            'code' => 'SMS_173136087'
        ],
        2 => [
            'name' => '登录确认验证码',
            'code' => 'SMS_173136086'
        ],
        3 => [
            'name' => '登录异常验证码',
            'code' => 'SMS_173136085'
        ],
        4 => [
            'name' => '用户注册验证码',
            'code' => 'SMS_173136084'
        ],
        5 => [
            'name' => '修改密码验证码',
            'code' => 'SMS_173136083'
        ],
        6 => [
            'name' => '信息变更验证码',
            'code' => 'SMS_173136082'
        ],
        7 => [
            'name' => '通知用户领取快递',
            'code' => 'SMS_175075929'
        ]
    ],
    'web_info' => [
        'host' => 'https://www.delcache.com',
        'ip' => '121.40.224.59',
    ],
    'car_type_list' => [
        '1' => '小巴车',
        '2' => '中巴车',
        '3' => '大巴车'
    ],
    'bus_order_status_list' => [
        '0' => '已取消',
        '1' => '待付款',//会员户待付款
        '2' => '待指派',//会员付款完成后，等待后台指派订单给司机
        '3' => '待接单',//后台订单指派给司机，等待司机接单
        '4' => '拒绝接单',//司机拒绝接单，等待重新派单
        '5' => '待确认',//司机接单，等待扫码确认
        '6' => '已绑定',//司机扫码确认
        '7' => '出行中',//出行中
        '8' => '已完成',
        '9' => '待退款'
    ],
    'bus_order_type_list' => [
        '1' => '旅游包车',
        '2' => '站点叫车',
        '3' => '定制班车'
    ],
    'bill_type_list' => [
        '1' => '旅游包车',
        '2' => '站点叫车',
        '3' => '定制班车',
        '4' => '送包裹'
    ],
    //公交站点类型
    'trace_type' => [
        '1' => '上行',
        '2' => '下行',
    ],
    'order_type' => [
        '1' => '发货订单',
        '2' => '取货订单'
    ],
    'order_status' => [
        'pending' => 1,//待指派
        'wait_send' => 2,//待配送
        'wait_bind' => 3,//配送中  司机到总站 扫码 绑定后的
        'sending' => 4,//配送中   司机 点击开始行程的
        'arrive' => 5,//已签收 服务站  服务站扫描二维码
        'complete' => 6,//订单完成  所有的包裹都被用户收了之后
    ],
    'package_status' => [
        'unpay' => 1,//待付款
        'wait' => 2,//待发货
        'wait_after_bind' => 3,//贴了标签的待发货
        'wait_get' => 4,//生成取货订单的 订单的待发货
        'arrive_to_get' => 5,//司机到达服务站 服务站扫码 然后让司机拿货
        'wait_arrive_center' => 6,//到达总站的待发货
        'wait_generate' => 7,//生成送货 订单的待发货
        'sending' => 8,//配送中
        'arrive' => 9,//已签收  服务站已签收
        'complete' => 10,//已完成用户取货
    ],
    'money' => [
        '1' => 3,
        '2' => 5,
        '3' => 6
    ],
    'order_source' => [
        '1' => '手动录入',
        '2' => '站点提交',
    ],
    'weight' => ['1' => "0-10kg", '2' => "11-15kg", '3' => "16-20kg"],
    'size' => ['1' => " ≤0.03m³", '2' => " ≤0.045m³", '3' => " ≤0.06m³"],
    'distance' => ['1' => "15km", '2' => "16-50km", '3' => "51㎞以上"],
];
