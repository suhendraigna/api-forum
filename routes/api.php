<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function($router){

    Route::prefix('auth')->group(function(){
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });

    Route::post('login', 'AuthController@login');
    Route::post('register', 'RegisterController@register');
    Route::apiResource('forums', 'ForumController');
    Route::apiResource('forums.comments', 'ForumCommentController');
});
