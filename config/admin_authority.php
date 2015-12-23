<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/9
 * Time: 上午10:00
 * 后台权限配置
 */

return [
    '权限管理' => [
        '管理组管理' => 'App\\Http\\Controllers\\Admin\\AdminUserGroupController',
        '管理员管理' => 'App\\Http\\Controllers\\Admin\\AdminUserController',
        '权限管理' => 'App\\Http\\Controllers\\Admin\\AdminAuthorityController',
    ],

    '产品库管理' => [
        '产品管理' => 'App\\Http\\Controllers\\Admin\\GoodsController',
        '厂商管理' => 'App\\Http\\Controllers\\Admin\\FirmController',
        'IP管理' => 'App\\Http\\Controllers\\Admin\\CartoonController',
        '规格管理' => 'App\\Http\\Controllers\\Admin\\NormsController',
        '标签管理' => 'App\\Http\\Controllers\\Admin\\TagController',
        '关键字管理' => 'App\\Http\\Controllers\\Admin\\KeywordController',
    ],

    '内容管理' => [
        '爆料管理' => 'App\\Http\\Controllers\\Admin\\ArticleController',
        '活动管理' => 'App\\Http\\Controllers\\Admin\\ActiveController',
    ],

    '供销社' => [
        '出售管理' => 'App\\Http\\Controllers\\Admin\\SellController',
        '求购管理' => 'App\\Http\\Controllers\\Admin\\BuyController',
    ],

    '频道管理' => [
        '文案' => 'App\\Http\\Controllers\\Admin\\OfficialTermsController',
        '首页管理' => 'App\\Http\\Controllers\\Admin\\HomeChannelController',
        '供销社管理' => 'App\\Http\\Controllers\\Admin\\SmcChannelController',
    ],

    '会员管理' => [
        '用户管理' => 'App\\Http\\Controllers\\Admin\\MemberController',
    ],

];