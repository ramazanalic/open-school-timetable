<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * 首頁
 */
Route::get('/', function()
{
	return View::make('index');
});

/**
 * 使用者帳號管理
 */

// 顯示教師列表
Route::get('/account', function()
{
	$teacherList = Teacher::all();
	return View::make('account_index')->with('teacherList', $teacherList);
});

//新增教師
Route::get('/account/add', function()
{
	return View::make('account_form')->with('formType', 'add');
});
