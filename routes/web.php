<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/admin/dashboard', 'HomeController@index')->name('home');

Route::group( ['middleware' => ['auth']], function() {
  Route::resource('/admin/users', 'UserController');
  Route::resource('/admin/roles', 'RoleController');
  Route::resource('/admin/posts', 'PostController');
  Route::resource('/admin/articals', 'ArticalController');
  Route::get('/admin/csv-upload/add', 'PostController@csvupload')->name('add');
  Route::post('/admin/csv-upload/import', 'PostController@import')->name('import');
  Route::get('/admin/isactive/{post_id}/{value}', ['uses'=>'PostController@isactive']);
  Route::get('/admin/user/passwordchange', 'UserController@changePassword')->name('passwordchange');
  Route::post('/admin/user/postCredentials', 'UserController@postCredentials')->name('postCredentials');
});
