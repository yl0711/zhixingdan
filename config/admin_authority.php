<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/9
 * Time: 上午10:00
 * 后台权限配置
 */

return [
    '用户管理' => [
        '部门管理' => 'App\\Http\\Controllers\\Admin\\AdminDepartmentController',
        '用户组管理' => 'App\\Http\\Controllers\\Admin\\AdminUserGroupController',
        '用户管理' => 'App\\Http\\Controllers\\Admin\\AdminUserController',
        '区域管理' => 'App\\Http\\Controllers\\Admin\\AdminAreaController',
        '修改密码' => 'App\\Http\\Controllers\\Admin\\PasswordController',
    ],
    '权限管理' => [
        '权限管理' => 'App\\Http\\Controllers\\Admin\\AdminAuthorityController',
    ],
/*
    '项目管理' => [
        '供应商管理' => 'App\\Http\\Controllers\\Admin\\CompanyController',
        '项目管理' => 'App\\Http\\Controllers\\Admin\\ProjectController',

    ],
*/
    '执行单管理' => [
        '执行单管理' => 'App\\Http\\Controllers\\Admin\\DocumentsController',
        '项目类型' => 'App\\Http\\Controllers\\Admin\\CategoryController',
        '成本构成' => 'App\\Http\\Controllers\\Admin\\CostController',
    ],

    '参数设置' => [
        '系统设置' => 'App\\Http\\Controllers\\Admin\\SettingController',
    ],

];