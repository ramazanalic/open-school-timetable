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

// 顯示新增教師表單
Route::get('/account/add', function()
{
	return View::make('account_form')->with('formType', 'add');
});

// 執行新增教師
Route::POST('/account/add', function()
{
	$validator = FormValidator::teacher(Input::all(), true);

	if ($validator->fails()) {
		return Redirect::to('account/add')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
	} else {
		$data = Input::only(array('teacher_name', 'teacher_account', 'teacher_password'));
		$data['teacher_password_hash'] = Hash::make($data['teacher_password']);
		unset($data['teacher_password']);

		if (Teacher::create($data)) {
			$message = '新增教師《' . $data['teacher_name'] . '》完成';
		} else {
			$message = '資料寫入錯誤';
		}

		return Redirect::to('account')->with('message', $message);
	}
});

// 顯示編輯教師表單
Route::get('/account/edit/{id}', function($id)
{
	$teacher = Teacher::find($id);
	return View::make('account_form')->with(array('formType' => 'edit', 'teacher' => $teacher));
});
