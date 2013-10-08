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
Route::group(array('prefix' => 'account'), function()
{
	// 顯示教師列表
	Route::get('/', function()
	{
		$teacherList = Teacher::all();
		return View::make('account_index')->with('teacherList', $teacherList);
	});

	// 顯示新增教師表單
	Route::get('/add', function()
	{
		return View::make('account_form')->with('teacher', NULL);
	});

	// 執行新增教師
	Route::post('/add', function()
	{
		$validator = FormValidator::teacher(Input::all(), true);

		if ($validator->fails()) {
			return Redirect::to('/account/add')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
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
	Route::get('/edit/{id}', function($id)
	{
		$teacher = Teacher::find($id);
		return View::make('account_form')->with('teacher', $teacher);
	});

	// 執行編輯教師
	Route::post('/edit/{id}', function($id)
	{
		$validator = FormValidator::teacher(Input::all(), Input::has('teacher_password'));

		if ($validator->fails()) {
			return Redirect::to('/account/edit/' . $id)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::only(array('teacher_name', 'teacher_account', 'teacher_password'));

			if (Input::has('teacher_password')) {
				$data['teacher_password_hash'] = Hash::make($data['teacher_password']);
			}

			unset($data['teacher_password']);

			$teacher = Teacher::find($id);
			if ($teacher->update($data)) {
				$message = '更新教師《' . $data['teacher_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('account')->with('message', $message);
		}
	});

	// 執行刪除教師
	Route::get('/delete/{id}', function($id)
	{
		$teacher = Teacher::find($id);
		$message = '刪除《' . $teacher->teacher_name . '》完成';
		$teacher->delete();
		return Redirect::to('account/')->with('message', $message);
	});

});

/**
 * 班級/年級管理
 */
Route::group(array('prefix' => 'class_year'), function()
{
	// 讀取年級列表
	$GLOBALS['yearList'] = Year::orderBy('year_name')->get();

	// 顯示年級列表、年級新增表單
	Route::get('/', function()
	{
		return View::make('class_year_index')->with(array('yearList' => $GLOBALS['yearList'], 'year' => NULL));
	});

	// 執行新增年級
	Route::post('/add_year', function()
	{
		$validator = FormValidator::year(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/class_year')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::only(array('year_name', 'course_time'));

			if (Year::create($data)) {
				$message = '新增年級《' . $data['year_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/class_year')->with('message', $message);
		}
	});

	// 顯示年級編輯標單、班級列表、新增班級表單
	Route::get('/view_year/{id}', function($id)
	{
		$viewData['year'] = Year::find($id);
		$viewData['classes'] = Year::find($id)->classes()->orderBy('classes_name')->get();
		$viewData['yearList'] = $GLOBALS['yearList'];
		return View::make('class_year_index')->with($viewData);
	});
});
