<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/22
 * Time: 下午1:18
 */


//登录和退登
Route::get('login', ['as'=>'login', 'uses'=>'Admin\AuthController@login']);
Route::post('login', ['as'=>'login', 'uses'=>'Admin\AuthController@dologin']);
Route::get('logout', ['as'=>'logout', 'uses'=>'Admin\AuthController@logout']);


Route::group(['namespace'=>'Admin', 'middleware'=>'adminauth'], function() {

    Route::get('/', 'AuthController@index');

    //用户管理
    Route::group(['prefix' => 'user'], function() {
        Route::get('/index', ['uses' => 'AdminUserController@index']);

        Route::match(['get', 'post'], '/add', ['uses' => 'AdminUserController@add']);

        Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'AdminUserController@modify'])->where('id', '[0-9]+');

        Route::get('status/{id}', ['uses'=>'AdminUserController@modifyStatus'])->where('id', '[0-9]+');
    });

    //用户组管理
    Route::group(['prefix' => 'usergroup'], function() {
        Route::get('/index', ['uses' => 'AdminUserGroupController@index']);

        Route::match(['get', 'post'], '/add', ['uses' => 'AdminUserGroupController@add']);

        Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'AdminUserGroupController@modify'])->where('id', '[0-9]+');

        Route::get('status/{id}', ['uses'=>'AdminUserGroupController@modifyStatus'])->where('id', '[0-9]+');
    });

    //部门管理
    Route::group(['prefix' => 'department'], function() {
        Route::get('/index', ['uses' => 'AdminDepartmentController@index']);

        Route::match(['get', 'post'], '/add', ['uses' => 'AdminDepartmentController@add']);

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
        Route::get('index', ['uses'=>'CompanyController@index']);

        Route::match(['get', 'post'], '/add', ['uses' => 'CompanyController@add']);

        Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'CompanyController@modify'])->where('id', '[0-9]+');

        Route::get('status/{id}', ['uses'=>'CompanyController@modifyStatus'])->where('id', '[0-9]+');
    });

    //项目管理
    Route::group(['prefix' => 'project'], function() {
        Route::get('index', ['uses'=>'ProjectController@index']);

        Route::match(['get', 'post'], '/add', ['uses' => 'ProjectController@add']);

        Route::match(['get', 'post'], 'modify/{id}', ['uses'=>'ProjectController@modify'])->where('id', '[0-9]+');

        Route::get('status/{id}', ['uses'=>'ProjectController@modifyStatus'])->where('id', '[0-9]+');
    });

});
