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
        '部门管理' => 'App\\Http\\Controllers\\Admin\\AdminDepartmentController',
        '管理组管理' => 'App\\Http\\Controllers\\Admin\\AdminUserGroupController',
        '管理员管理' => 'App\\Http\\Controllers\\Admin\\AdminUserController',
        '权限管理' => 'App\\Http\\Controllers\\Admin\\AdminAuthorityController',
    ],

];