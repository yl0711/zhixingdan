<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/22
 * Time: 下午1:18
 */

//, 'middleware'=>'adminlog'
// 后台路由组
Route::group(['namespace'=>'Admin'], function() {

    //登录和退登
    Route::get('login', ['as'=>'login', 'uses'=>'AuthController@login']);
    Route::post('login', ['as'=>'login', 'uses'=>'AuthController@dologin']);
    Route::get('logout', ['as'=>'logout', 'uses'=>'AuthController@logout']);

    // 需要进行用户身份验证
    Route::group(['middleware'=>['adminauth', 'adminAccessLog']], function() {

        Route::get('/', 'AuthController@index');

        //用户管理
        Route::group(['prefix' => 'user'], function() {
            Route::match(['get', 'post'], 'index', ['uses' => 'AdminUserController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'AdminUserController@add']);

            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'AdminUserController@modify'])->where('id', '[0-9]+');
            Route::match(['get', 'post'], 'parent/{id}', ['uses'=>'AdminUserController@parentUser'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'AdminUserController@modifyStatus'])->where('id', '[0-9]+');
        });

        //用户组管理
        Route::group(['prefix' => 'usergroup'], function() {
            Route::match(['get', 'post'], 'index', ['uses' => 'AdminUserGroupController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'AdminUserGroupController@add']);

            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'AdminUserGroupController@modify'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'AdminUserGroupController@modifyStatus'])->where('id', '[0-9]+');
        });

        //部门管理
        Route::group(['prefix' => 'department'], function() {
            Route::match(['get', 'post'], 'index', ['uses' => 'AdminDepartmentController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'AdminDepartmentController@add']);

            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'AdminDepartmentController@modify'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'AdminDepartmentController@modifyStatus'])->where('id', '[0-9]+');
        });

        //权限管理
        Route::group(['prefix'=>'authority'], function() {
            Route::get('index', ['uses'=>'AdminAuthorityController@index']);
            Route::get('refresh', ['uses'=>'AdminAuthorityController@refreshList']);

            Route::get('user/{uid}', ['uses'=>'AdminAuthorityController@userAuthority'])->where('uid','[1-9]+');
            Route::get('group/{gid}', ['uses'=>'AdminAuthorityController@groupAuthority'])->where('gid','[1-9]+');

            Route::post('user/{uid?}', ['uses'=>'AdminAuthorityController@userAuthority']);
            Route::post('group/{gid?}', ['uses'=>'AdminAuthorityController@groupAuthority']);
        });

        //供应商管理
        Route::group(['prefix' => 'company'], function() {
            Route::match(['get', 'post'], 'index', ['uses'=>'CompanyController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'CompanyController@add']);

            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'CompanyController@modify'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'CompanyController@modifyStatus'])->where('id', '[0-9]+');
        });

        //项目管理
        Route::group(['prefix' => 'project'], function() {
            Route::match(['get', 'post'], 'index', ['uses'=>'ProjectController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'ProjectController@add']);
            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'ProjectController@modify'])->where('id', '[0-9]+');

            Route::get('member/{id}', ['uses'=>'ProjectController@member'])->where('id', '[0-9]+');
            Route::match(['get', 'post'], 'addmember/{id}', ['uses'=>'ProjectController@addMember'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'ProjectController@modifyStatus'])->where('id', '[0-9]+');
        });

        //执行单管理
        Route::group(['prefix'=>'documents'], function() {
            Route::match(['get', 'post'], 'index', ['uses'=>'DocumentsController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'DocumentsController@add']);
            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'DocumentsController@modify'])->where('id', '[0-9]+');
            Route::match(['get', 'post'], 'review', ['uses'=>'DocumentsController@review']);

            Route::match(['get', 'post'], 'cost/{id?}', ['uses'=>'DocumentsController@docmentsCost'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'DocumentsController@modifyStatus'])->where('id', '[0-9]+');
            Route::get('process/{id}', ['uses'=>'DocumentsController@process'])->where('id', '[0-9]+');
            Route::get('show/{id}', ['uses'=>'DocumentsController@show'])->where('id', '[0-9]+');
            Route::get('download/{id}', ['uses'=>'DocumentsController@download'], function () {
                return (new Response($content, $status))->header('Content-Type', 'text/html; charset=GBK');
            })->where('id', '[0-9]+');
        });

        //项目类型管理
        Route::group(['prefix' => 'category'], function() {
            Route::match(['get', 'post'], 'index', ['uses'=>'CategoryController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'CategoryController@add']);
            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'CategoryController@modify'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'CategoryController@modifyStatus'])->where('id', '[0-9]+');
        });

        //成本构成管理
        Route::group(['prefix' => 'cost'], function() {
            Route::match(['get', 'post'], 'index', ['uses'=>'CostController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'CostController@add']);
            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'CostController@modify'])->where('id', '[0-9]+');

            Route::get('status/{id}', ['uses'=>'CostController@modifyStatus'])->where('id', '[0-9]+');
        });

        //区域管理
        Route::group(['prefix' => 'area'], function() {
            Route::match(['get', 'post'], 'index', ['uses'=>'AdminAreaController@index']);

            Route::match(['get', 'post'], 'add', ['uses' => 'AdminAreaController@add']);
            Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'AdminAreaController@modify'])->where('id', '[0-9]+');
        });
    });

});
