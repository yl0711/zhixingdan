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

Route::group(['namespace'=>'Admin', 'middleware'=>'auth'],function() {

    Route::get('/', 'AuthController@index');

    Route::group(['prefix' => 'user'], function() {
        Route::get('/index', ['use' => 'AdminUserController@index']);
    });

    Route::group(['prefix' => 'usergroup'], function() {
        Route::get('/index', ['use' => 'AdminUserGroupController@index']);
    });

    Route::group(['prefix' => 'department'], function() {
        Route::get('/index', ['use' => 'AdminDepartmentController@index']);
    });

});