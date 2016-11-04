<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return view('welcome');
    }
});

Auth::routes();
//Route::post('/login', 'Auth\LoginController@login');
//Route::get('/login', 'Auth\LoginController@showLoginForm');
//Route::post('/logout', 'Auth\LoginController@logout');
//
//Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
//Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
//Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//
//Route::get('/register', 'HomeController@index');
//Route::post('/register', 'HomeController@index');
//
Route::get('/user/profile', 'UserController@profile');
Route::patch('/user/profile', 'UserController@updateProfile');

Route::get('home', 'HomeController@index');


// Task
Route::get('tasks', 'TaskController@index');
Route::get('tasks/create', 'TaskController@create');
Route::post('tasks', 'TaskController@store');
Route::get('tasks/{task_id}', 'TaskController@show');
Route::delete('tasks/{task_id}', 'TaskController@destroy');
Route::post('tasks/{task_id}/retry', 'TaskController@retry');
Route::post('tasks/{task_id}/terminate', 'TaskController@terminate');
Route::post('tasks/{task_id}/topbottom', 'TaskController@createTopBottom');
