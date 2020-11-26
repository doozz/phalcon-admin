<?php

return [
    'baseInfo' => [
        'domain' => '',
        'cryptKey' => '#1dj9#crf%8=k-a%',
		'cookieDomain' => '/',
		'logFile' => ''
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

    'callShield' => [
        'maxNums' => 10,  // 请求不能超过10次
        'timeRange' => 1000, // 1000毫秒内
        'msg' => 'Request too quickly'
    ],

    'nsq' => [
        'host' => '127.0.0.1',
        'port' => 4150
    ],

    //上传图片大小的最大值 (6M)
    'allowedFileSize' => 1024*1024*6,
    'uploadImgPath' => 'public/images',
    'uploadIconPath' => 'public/images/icon/',
    'uploadCoverImgPath' => 'public/images/cover/',

    'videoType' => [1=>'电影', 2=>'电视剧', 3=>'综艺', 4=>'动漫', 5=>'纪录片'],
    'videoFlag' => [1=>'vip', 2=>'高清', 3=>'免费', 4=>'预告'],
    'modelType' => [1=>'top banner',3=> '内含banner',5=>'不含banner',7=>'独立banner'],
    'modelUri' => ['special'=>'模块','webview'=> '外部链接', 'detail'=>'影片详情'],

    '7niudomain' => 'http://pta8fcggg.bkt.clouddn.com/'
];
