<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('welcome');
});
// 認證路由...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// 註冊路由...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
// password
Route::controllers([
   'password' => 'Auth\PasswordController',
]);
 //Password reset link request routes...
//Route::get('password/email', 'Auth\PasswordController@getEmail');
//Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::group(['prefix' => 'admin','middleware' => 'auth'], function () {//
    Route::get('users', function ()    {
        // Matches The "/admin/users" URL
    });
    Route::get('dashboard', ['as' => 'dashboard', function () {
        // Route named "admin/dashboard"
    }]);
    Route::get('home', ['as' => 'home', function () {
        // Route named "admin/roles"
        return view('admin.home');
    }]);

    Route::resource('permissions', 'PermissionController');
    Route::resource('roles', 'RoleController');
    Route::resource('platforms', 'PlatformController');
    Route::resource('customers', 'CustomerController');
    Route::resource('feeds', 'FeedController');
    //This route will register a "nested" resource that may be accessed with URLs like the following: photos/{photos}/comments/{comments}.
    Route::resource('feeds.product', 'FeedProductController');
    Route::resource('users', 'UserController');
   
});

// for server side DataTable
//Route::get('/feeds/ajaxData', 'FeedProductController@ajaxData');
Route::match(['get', 'post'],'feeds/ajaxData', [
    'as' => 'admin.feeds.ajaxData','uses' => 'FeedProductController@ajaxData'
]);
Route::match(['get', 'post'],'feeds/ajaxProduct_enable', [
    'as' => 'admin.feeds.ajaxProduct_enable','uses' => 'FeedProductController@ajaxProduct_enable'
]);
Route::match(['get', 'post'],'feeds/ajaxProduct_disable', [
    'as' => 'admin.feeds.ajaxProduct_disable','uses' => 'FeedProductController@ajaxProduct_disable'
]);
// 之後再嘗試改用middleware 來判斷分流，是否可行
Route::get('/appier/{feed_id}', 'MediaController@appier', function ($feed_id) {
    //return view('welcome');
});
Route::get('/google/{feed_id}', 'MediaController@google', function ($feed_id) {
    //return view('welcome');
});

