<?php

return [
    'baseInfo' => [
        'domain' => '',
        'cryptKey' => '#1dj9#crf%8=k-a%',
        'cookieDomain' => '/',
    ],

    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'fans',
        'user' => 'root',
        'pass' => '123456',
    ],

    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'db' => 0,
    ],

    'uploadImgPath' => 'public/images',

    'callShield' => [
        'maxNums' => 10,  // 请求不能超过10次
        'timeRange' => 1000, // 1000毫秒内
        'msg' => 'Request too quickly'
    ],
];
