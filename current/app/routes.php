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
	return View::make('class_view');
});

/**
 *
 */
Route::get('/class_view', function()
{
	return View::make('class_view');
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
		return View::make('class_year_index')->with(array('yearList' => $GLOBALS['yearList'], 'year' => NULL));
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
});
