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
 * 首頁，導向班級課表查詢
 */
Route::get('/', function()
{
	return View::make('class_table');
});

/**
 * 班級課表查詢
 */
Route::get('/class_table', function()
{
	return View::make('class_table');
});

/**
 * 教室課表查詢
 */
Route::get('/classroom_table', function()
{
	return View::make('classroom_table');
});

/**
 * 教師課表查詢
 */
Route::get('/teacher_table', function()
{
	return View::make('teacher_table');
});

/**
 * 登入畫面
 */
Route::group(array('prefix' => 'login'), function()
{
	Route::get('/', function()
	{
		return View::make('login');
	});

	Route::post('/', function()
	{
		$credentials = array(
			'teacher_account' => Input::get('teacher_account'),
			'password' => Input::get('teacher_password')
		);

		if (Auth::attempt($credentials, true)) {
			return Redirect::to('/')->with('message', '登入完成');
			;
		} else {
			return Redirect::to('login')->with('message', '帳號密碼錯誤');
			;
		}
		return Redirect::to('/');
	});
});

/**
 * 登出
 */
Route::get('logout', function()
{
	$teahcerName = Auth::user()->teacher_name;
	Auth::logout();
	return Redirect::to('/')->with('message', '使用者《' . $teahcerName . '》登出');
});

/**
 * 帳號管理
 */
Route::group(array('prefix' => 'account'), function()
{
	// 顯示全部教師列表
	Route::get('/', function()
	{
		return Redirect::to('account/view_title/all');
	});

	// 依職稱顯示教師列表
	Route::get('view_title/{titleId}', function($titleId)
	{
		$teacher = ($titleId == 'all') ? Teacher::orderBy('teacher_name') : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name');
		$viewData['teacherList'] = $teacher->get();
		$viewData['titleList'] = Title::orderBy('title_name')->get();
		$viewData['titleId'] = $titleId;
		return View::make('account_index')->with($viewData);
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
			$data = Input::except('teacher_password', 'teacher_password_confirmation');
			$data['teacher_password_hash'] = Hash::make(Input::get('teacher_password'));

			if (Teacher::create($data)) {
				$message = '新增教師《' . $data['teacher_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			Teacher::syncClasses();

			return Redirect::to('account/view_title/' . Teacher::getLastTitleId())->with('message', $message);
		}
	});

	// 顯示編輯教師表單
	Route::get('/edit/{id}/titleId/{titleId}', function($id, $titleId)
	{
		$viewData['teacher'] = Teacher::find($id);
		$viewData['titleId'] = $titleId;
		return View::make('account_form')->with($viewData);
	});

	// 執行編輯教師
	Route::post('/edit/{id}/titleId/{titleId}', function($id, $titleId)
	{
		$validator = FormValidator::teacher(Input::all(), Input::has('teacher_password'));

		if ($validator->fails()) {
			return Redirect::to('/account/edit/' . $id . '/titleId/' . $titleId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::except('teacher_password', 'teacher_password_confirmation');

			if (Input::has('teacher_password')) {
				$data['teacher_password_hash'] = Hash::make(Input::get('teacher_password'));
			}

			$teacher = Teacher::find($id);
			if ($teacher->update($data)) {
				$message = '更新教師《' . $data['teacher_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			Teacher::syncClasses();

			return Redirect::to('account/view_title/' . $titleId)->with('message', $message);
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

	// 執行新增職稱
	Route::post('/add_title', function()
	{
		$validator = FormValidator::title(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/account')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			if (Title::create($data)) {
				$message = '新增職稱《' . $data['title_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('account')->with('message', $message);
		}
	});

	// 執行編輯職稱
	Route::post('/update_title/{titleId}', function($titleId)
	{
		$validator = FormValidator::title(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/account/view_title/' . $title_id)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			$title = Title::find($titleId);
			if ($title->update($data)) {
				$message = '更新職稱《' . $data['title_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/account/view_title/' . $titleId)->with('message', $message);
		}
	});

	// 執行刪除職稱
	Route::get('/delete_title/{titleId}', function($titleId)
	{
		$title = Title::find($titleId);
		$message = '刪除《' . $title->title_name . '》完成';
		$title->delete();
		return Redirect::to('account/')->with('message', $message);
	});
});

/**
 * 班級、年級管理
 */
Route::group(array('prefix' => 'class_year'), function()
{
	// 讀取年級列表
	$GLOBALS['yearList'] = Year::orderBy('year_name')->get();

	// 顯示年級列表、年級新增表單
	Route::get('/', function()
	{
		return View::make('class_year_index')->with(array(
			'yearList' => $GLOBALS['yearList'],
			'year' => NULL
		));
	});

	// 執行新增年級
	Route::post('/add_year', function()
	{
		$validator = FormValidator::year(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/class_year')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			if (Year::create($data)) {
				$message = '新增年級《' . $data['year_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/class_year')->with('message', $message);
		}
	});

	// 顯示年級編輯標單、班級列表、新增班級表單
	Route::get('/view_year/{yearId}', function($yearId)
	{
		$viewData['year'] = Year::find($yearId);
		$viewData['classes'] = Year::find($yearId)->classes()->orderBy('classes_name')->get();
		$viewData['yearList'] = $GLOBALS['yearList'];
		return View::make('class_year_index')->with($viewData);
	});

	// 執行編輯年級
	Route::post('/update_year/{yearId}', function($yearId)
	{
		$validator = FormValidator::year(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/class_year/view_year/' . $yearId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			$year = Year::find($yearId);
			if ($year->update($data)) {
				$message = '更新《' . $data['year_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/class_year/view_year/' . $yearId)->with('message', $message);
		}
	});

	// 執行新增班級
	Route::post('/add_classes/{yearId}', function($yearId)
	{
		$validator = FormValidator::classes(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/class_year/view_year/' . $yearId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();
			$data['year_id'] = $yearId;

			if (Classes::create($data)) {
				$message = '新增班級《' . $data['classes_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			Classes::syncTeacher();

			return Redirect::to('/class_year/view_year/' . $yearId)->with('message', $message);
		}
	});

	// 執行編輯班級
	Route::post('/update_classes/{classesId}/{yearId}', function($classesId, $yearId)
	{
		$validator = FormValidator::classes(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/class_year/view_year/' . $yearId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			$year = Classes::find($classesId);
			if ($year->update($data)) {
				$message = '更新《' . $data['classes_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			Classes::syncTeacher();

			return Redirect::to('/class_year/view_year/' . $yearId)->with('message', $message);
		}
	});

	// 執行刪除班級
	Route::get('/delete_classes/{classesId}/{yearId}', function($classesId, $yearId)
	{
		$classes = Classes::find($classesId);
		$message = '刪除《' . $classes->classes_name . '》完成';
		$classes->delete();
		return Redirect::to('/class_year/view_year/' . $yearId)->with('message', $message);
	});

	// 執行刪除年級
	Route::get('/delete_year/{yearId}', function($yearId)
	{
		$year = Year::find($yearId);
		$message = '刪除《' . $year->year_name . '》完成';
		$year->delete();
		return Redirect::to('/class_year')->with('message', $message);
	});

});

/**
 * 課程管理
 */
Route::group(array('prefix' => 'course'), function()
{
	// 顯示課程名稱
	Route::get('/', function()
	{
		$courseList = Course::orderBy('course_name')->get();
		return View::make('course_index')->with(array('courseList' => $courseList));
	});

	// 執行新增課程
	Route::post('/add', function()
	{
		$validator = FormValidator::course(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/course')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			if (Course::create($data)) {
				$message = '新增課程《' . $data['course_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/course')->with('message', $message);
		}
	});

	// 執行編輯課程
	Route::post('/edit/{id}', function($id)
	{
		$validator = FormValidator::course(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/course')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();
			$course = Course::find($id);

			if ($course->update($data)) {
				$message = '更新課程《' . $data['course_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/course')->with('message', $message);
		}
	});

	// 執行刪除課程
	Route::get('/delete/{id}', function($id)
	{
		$course = Course::find($id);
		$message = '刪除《' . $course->course_name . '》完成';
		$course->delete();
		return Redirect::to('/course')->with('message', $message);
	});
});

/**
 * 教室管理
 */
Route::group(array('prefix' => 'classroom'), function()
{
	// 顯示教室名稱
	Route::get('/', function()
	{
		$classroomList = Classroom::orderBy('classroom_name')->get();
		return View::make('classroom_index')->with(array('classroomList' => $classroomList));
	});

	// 執行新增教室
	Route::post('/add', function()
	{
		$validator = FormValidator::classroom(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/classroom')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			if (Classroom::create($data)) {
				$message = '新增教室《' . $data['classroom_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/classroom')->with('message', $message);
		}
	});

	// 執行編輯教室
	Route::post('/edit/{id}', function($id)
	{
		$validator = FormValidator::classroom(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/classroom')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();
			$classroom = Classroom::find($id);

			if ($classroom->update($data)) {
				$message = '更新教室《' . $data['classroom_name'] . '》完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/classroom')->with('message', $message);
		}
	});

	// 執行刪除教室
	Route::get('/delete/{id}', function($id)
	{
		$classroom = Classroom::find($id);
		$message = '刪除《' . $classroom->classroom_name . '》完成';
		$classroom->delete();
		return Redirect::to('/classroom')->with('message', $message);
	});
});

/**
 * 排課設定
 */
Route::group(array('prefix' => 'timetable'), function()
{
	// 顯示全部教師列表
	Route::get('/', function()
	{
		return Redirect::to('timetable/view_title/all');
	});

	// 依職稱顯示教師列表
	Route::get('view_title/{titleId}', function($titleId)
	{
		$teacher = ($titleId == 'all') ? Teacher::orderBy('teacher_name') : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name');
		$viewData['teacherList'] = $teacher->get();
		$viewData['titleList'] = Title::orderBy('title_name')->get();
		$viewData['titleId'] = $titleId;
		return View::make('timetable_index')->with($viewData);
	});

	// 取得排課設定編輯表單（AJAX）
	Route::get('edit/{teacherId}/{courseUnitId}', function($teacherId, $courseUnitId)
	{
		$viewData['teacher'] = Teacher::find($teacherId);
		$viewData['titleId'] = ($viewData['teacher']->title) ? $viewData['teacher']->title->title_id : 0;
		$viewData['course_units'] = $viewData['teacher']->courseunit;
		$viewData['course_unit'] = Courseunit::find($courseUnitId);
		return View::make('course_unit_form')->with($viewData);
	});

	// 執行更新排課設定
	Route::post('edit/{teacherId}/{courseUnitId}', function($teacherId, $courseUnitId)
	{
		$validator = FormValidator::courseUnit(Input::all());

		if ($validator->fails()) {
			print_r(Session::get('conflictError'));
			exit ;
			return Redirect::to('/timetable/view_title/' . Teacher::find($teacherId)->title->title_id . '/#' . $teacherId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();
			$courseUnit = Courseunit::find($courseUnitId);
			if ($courseUnit->update($data)) {
				$message = '更新排課設定完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/timetable/view_title/' . Teacher::find($teacherId)->title->title_id . '/#' . $teacherId)->with('message', $message);
		}
	});

	// 顯示教師排課設定（AJAX）
	Route::get('get_course_unit_form/{teacherId}', function($teacherId)
	{
		$viewData['teacher'] = Teacher::find($teacherId);
		$viewData['titleId'] = ($viewData['teacher']->title) ? $viewData['teacher']->title->title_id : 0;
		$viewData['course_units'] = $viewData['teacher']->courseunit;
		return View::make('course_unit_form')->with($viewData);
	});

	// 執行新增排課設定
	Route::post('/add/{titleId}/{teacherId}', function($titleId, $teacherId)
	{
		$validator = FormValidator::courseUnit(Input::all());

		if ($validator->fails()) {
			return Redirect::to('/timetable/view_title/' . $titleId . '/#' . $teacherId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
		} else {
			$data = Input::all();

			if (Courseunit::create($data)) {
				$message = '新增完成';
			} else {
				$message = '資料寫入錯誤';
			}

			return Redirect::to('/timetable/view_title/' . $titleId . '/#' . $teacherId)->with('message', $message);
		}
	});

	// 執行刪除排課設定
	Route::get('/delete/{id}', function($id)
	{
		$courseUnit = Courseunit::find($id);
		$url = '/timetable/view_title/' . $courseUnit->teacher->title->title_id . '/#' . $courseUnit->teacher->teacher_id;
		$message = '刪除排課設定';
		$courseUnit->delete();
		return Redirect::to($url)->with('message', $message);
	});
});
