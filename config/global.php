<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/22
 * Time: 下午1:16
 */

return [

    'HTTP_HOST'=>$_SERVER['HTTP_HOST'],
    'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
    'DS'=>DIRECTORY_SEPARATOR,
    'BASE_DIR'=>dirname(__DIR__) ,
    'IMG_DIR'=> dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'image',
    'LOGS_DIR'=>dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs',
    'DOMAIN' => [
        'ADMIN' => 'admin.test.com',
        'IMAGE' => 'image.test.com',
    ],
    'PAGE_SIZE'=>10,
    'API_LIMIT'=>2,
    'FILTER'=>'trim,addslashes,htmlspecialchars', // 过滤函数
    'UNFILTER'=>'htmlspecialchars_decode,stripslashes',

    // 全局变量
    'CONSTANTS_ADMIN_USER'  => [],
    'CONSTANTS_ADMIN_USER_GROUP'  => [],
    'CONSTANTS_ADMIN_USER_AUTHORITY'  => [],
    'CONSTANTS_ADMIN_CURRENT_AUTHORITY'  => [],

    'CONSTANTS_USER'  => [],

    'ARTICLE_STATUS' => 0,

    'PHOTO_SIZE' => [
        'BIG' => [180,180],
        'SMALL' => [60,60],
    ],

    'PHOTO_SEX' => [
        [
            'BIG' => '/static/head.png',
            'SMALL' => '/static/head.png', //保密
        ],
        [
            'BIG' => '/static/head.png',
            'SMALL' => '/static/head.png',//女
        ],
        [
            'BIG' => '/static/head.png',
            'SMALL' => '/static/head.png',//男
        ],

    ],
];
