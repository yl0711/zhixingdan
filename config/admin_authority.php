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

];