<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/22
 * Time: 下午1:18
 */


Route::group(['namespace'=>'Api'], function() {

    Route::group(['prefix' => 'get'], function() {
        Route::get('user/{id}', ['uses' => 'AdminUserController@getUser'])->where('id', '[0-9]+');
    });

});
