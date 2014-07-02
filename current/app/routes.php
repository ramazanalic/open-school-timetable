<?php
/**
 * 首頁，導向班級課表查詢
 */
Route::get('/', function()
{
	return Redirect::to('class_table')->with('message', Session::get('message'));
});

/**
 * 班級課表查詢
 */
Route::group(array('prefix' => 'class_table'), function()
{
	Route::get('/', function()
	{
		$viewData['yearList'] = Year::orderBy('year_name')->get();
		return View::make('class_table', $viewData);
	});

	Route::get('/{yearId}', function($yearId)
	{
		$viewData['yearList'] = Year::orderBy('year_name')->get();
		$viewData['classes'] = Year::find($yearId)->classes()->orderBy('classes_name')->get();
		$viewData['yearId'] = $yearId;
		return View::make('class_table', $viewData);
	});

	Route::get('/{yearId}/{classesId}', function($yearId, $classesId)
	{
		$viewData['yearList'] = Year::orderBy('year_name')->get();
		$viewData['classes'] = Year::find($yearId)->classes()->orderBy('classes_name')->get();
		$viewData['yearId'] = $yearId;
		$viewData['classesId'] = $classesId;

		// 處理原始資料
		$result = json_decode(file_get_contents(__DIR__ . './storage/result.json'), true);
		$viewData['classTimeTable'] = array_fill(0, 35, null);
		foreach ($result as $course) {
			if ($course['classes_id'] == $classesId) {
				for ($i = 0; $i < $course['combination']; $i++) {
					$viewData['classTimeTable'][$course['course_time'] + $i] = $course;
				}
			}
		}

		return View::make('class_table', $viewData);
	});
});

/**
 * 教師課表查詢
 */
Route::group(array('prefix' => 'teacher_table'), function()
{
	Route::get('/', function()
	{
		$viewData['titleList'] = Title::orderBy('title_name')->get();
		return View::make('teacher_table', $viewData);
	});

	Route::get('/{titleId}', function($titleId)
	{
		$viewData['titleList'] = Title::orderBy('title_name')->get();
		$viewData['teacherList'] = ($titleId == 'all') ? Teacher::orderBy('teacher_name')->get() : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name')->get();
		$viewData['titleId'] = $titleId;
		return View::make('teacher_table', $viewData);
	});

	Route::get('/{titleId}/{teacherId}', function($titleId, $teacherId)
	{
		$viewData['titleList'] = Title::orderBy('title_name')->get();
		$viewData['teacherList'] = ($titleId == 'all') ? Teacher::orderBy('teacher_name')->get() : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name')->get();
		$viewData['titleId'] = $titleId;
		$viewData['teacherId'] = $teacherId;

		// 處理原始資料
		$result = json_decode(file_get_contents(__DIR__ . './storage/result.json'), true);
		$viewData['teacherTimeTable'] = array_fill(0, 35, null);
		foreach ($result as $course) {
			if ($course['teacher_id'] == $teacherId) {
				for ($i = 0; $i < $course['combination']; $i++) {
					$viewData['teacherTimeTable'][$course['course_time'] + $i] = $course;
				}
			}
		}

		return View::make('teacher_table', $viewData);
	});
});

/**
 * 教室課表查詢
 */
Route::group(array('prefix' => 'classroom_table'), function()
{
	Route::get('/', function()
	{
		$viewData['classsroomList'] = Classroom::orderBy('classroom_name')->get();
		return View::make('classroom_table', $viewData);
	});

	Route::get('/{classroomId}', function($classroomId)
	{
		$viewData['classsroomList'] = Classroom::orderBy('classroom_name')->get();
		$viewData['classroomId'] = $classroomId;

		// 處理原始資料
		$result = json_decode(file_get_contents(__DIR__ . './storage/result.json'), true);
		$viewData['classsroomTimeTable'] = array_fill(0, 35, null);
		foreach ($result as $course) {
			if ($course['classroom_id'] == $classroomId) {
				$viewData['classsroomTimeTable'][$course['course_time']][] = $course;
			}
		}

		return View::make('classroom_table', $viewData);
	});
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
		$credentials = array('teacher_account' => Input::get('teacher_account'), 'password' => Input::get('teacher_password'));

		if (Auth::attempt($credentials)) {
			return Redirect::to('/')->with('message', '登入完成');
		} else {
			return Redirect::to('login')->with('message', '帳號密碼錯誤');
		}

		return Redirect::to('/');
	});
});

/**
 * 登出
 */
Route::get('logout', function()
{
	if (Auth::check()) {
		$teahcerName = Auth::user()->teacher_name;
		Auth::logout();
		return Redirect::to('/')->with('message', '使用者《' . $teahcerName . '》登出');
	} else {
		return Redirect::to('/');
	}
});

/**
 * 教師排課需求設定（需要登入）
 */
Route::group(array('prefix' => 'teacher_require', 'before' => 'auth'), function()
{
	// 顯示教師排課需求設定表單
	Route::get('/{teacherId}', function($teacherId)
	{
		if (Auth::check() && Auth::user()->teacher_id == $teacherId) {
			return View::make('teacher_require');
		} else {
			return Redirect::to('/')->with('message', '請先進行登入');
		}
	});

	// 更新教師排課需求
	Route::post('/{teacherId}', function($teacherId)
	{
		if (Auth::check() && Auth::user()->teacher_id == $teacherId) {
			$validator = FormValidator::teacherRequire(Input::all());

			if ($validator->fails()) {
				return Redirect::to('teacher_require/' . $teacherId)->withErrors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				$data = Input::all();

				$teacher = Teacher::find(Auth::user()->teacher_id);

				if ($teacher->update($data)) {
					$message = '設定《' . Auth::user()->teacher_name . '》的排課需求完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('teacher_require/' . $teacherId)->with('message', $message);
			}
		} else {
			return Redirect::to('/')->with('message', '請先進行登入');
		}
	});
});

/**
 * 變更密碼（需要登入）
 */
Route::group(array('prefix' => 'change_password', 'before' => 'auth'), function()
{
	// 顯示變更密碼表單
	Route::get('/{teacherId}', function($teacherId)
	{
		if (Auth::check() && Auth::user()->teacher_id == $teacherId) {
			return View::make('change_password');
		} else {
			return Redirect::to('/')->with('message', '請先進行登入');
		}
	});

	// 執行變更密碼
	Route::post('/{teacherId}', function($teacherId)
	{
		if (Auth::check() && Auth::user()->teacher_id == $teacherId) {
			$validator = FormValidator::password(Input::all());

			if ($validator->fails()) {
				return Redirect::to('change_password/' . $teacherId)->withErrors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				$data['teacher_password_hash'] = Hash::make(Input::get('teacher_password'));

				$teacher = Teacher::find(Auth::user()->teacher_id);

				if ($teacher->update($data)) {
					$message = '設定《' . Auth::user()->teacher_name . '》密碼完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('change_password/' . $teacherId)->with('message', $message);
			}
		} else {
			return Redirect::to('/')->with('message', '請先進行登入');
		}
	});
});

if (Auth::check() && Auth::user()->teacher_privilege < 16) {
	/**
	 * 帳號管理
	 */
	Route::group(array('prefix' => 'account', 'before' => 'auth'), function()
	{
		// 顯示全部教師列表
		Route::get('/', function()
		{
			return Redirect::to('account/view_title/all');
		});

		// 依職稱顯示教師列表
		Route::get('view_title/{titleId}', function($titleId)
		{
			if ($titleId == 'all') {
				$viewData['teacherList'] = Teacher::orderBy('teacher_name')->with('title', 'classes')->get();
			} else {
				$viewData['teacherList'] = Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name')->with('title', 'classes')->get();
			}
			$viewData['teacherList'] = ($titleId == 'all') ? Teacher::orderBy('teacher_name')->get() : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name')->get();
			$viewData['titleList'] = Title::orderBy('title_name')->get();
			$viewData['titleId'] = $titleId;
			return View::make('account')->with($viewData);

		});

		// 顯示新增教師表單
		Route::get('/add/{titleId}', function($titleId)
		{
			$viewData['titleId'] = $titleId;
			$viewData['teacher'] = null;
			return View::make('account_form')->with($viewData);
		});

		// 執行新增教師
		Route::post('/add/{titleId}', function($titleId)
		{
			$validator = FormValidator::teacher(Input::all(), true);

			if ($validator->fails()) {
				return Redirect::to('/account/add/' . $titleId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
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
			$viewData['teacherCourseunit'] = $viewData['teacher']->courseunit;
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
	Route::group(array('prefix' => 'class_year', 'before' => 'auth'), function()
	{
		// 讀取年級列表
		$GLOBALS['yearList'] = Year::orderBy('year_name')->get();

		// 顯示年級列表、年級新增表單
		Route::get('/', function()
		{
			return View::make('class_year')->with(array('yearList' => $GLOBALS['yearList'], 'year' => NULL));
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
			return View::make('class_year')->with($viewData);
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
	Route::group(array('prefix' => 'course', 'before' => 'auth'), function()
	{
		// 顯示課程名稱
		Route::get('/', function()
		{
			$courseList = Course::orderBy('course_name')->get();
			return View::make('course')->with(array('courseList' => $courseList));
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
	Route::group(array('prefix' => 'classroom', 'before' => 'auth'), function()
	{
		// 顯示教室名稱
		Route::get('/', function()
		{
			$classroomList = Classroom::orderBy('classroom_name')->get();
			return View::make('classroom')->with(array('classroomList' => $classroomList, 'classroom' => null));
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

		// 顯示編輯教師
		Route::get('/edit/{id}', function($id)
		{
			$viewData['classroomList'] = Classroom::orderBy('classroom_name')->get();
			$viewData['classroom'] = Classroom::find($id);
			$viewData['classroomCourseunit'] = $viewData['classroom']->courseunit;
			return View::make('classroom')->with($viewData);
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
	Route::group(array('prefix' => 'timetable', 'before' => 'auth'), function()
	{
		// 顯示全部教師列表
		Route::get('/', function()
		{
			return Redirect::to('timetable/view_title/all');
		});

		// 依職稱顯示教師列表
		Route::get('view_title/{titleId}', function($titleId)
		{
			$teacherList = ($titleId == 'all') ? Teacher::orderBy('teacher_name') : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name');
			$viewData['teacherList'] = $teacherList->get();
			$viewData['titleList'] = Title::orderBy('title_name')->get();
			$viewData['titleId'] = $titleId;
			return View::make('timetable')->with($viewData);
		});

		// 顯示教師排課清單
		Route::get('view_title/{titleId}/{teacherId}', function($titleId, $teacherId)
		{
			$teacherList = ($titleId == 'all') ? Teacher::orderBy('teacher_name') : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name');
			$viewData['teacherList'] = $teacherList->get();
			$viewData['titleList'] = Title::orderBy('title_name')->get();
			$viewData['titleId'] = $titleId;
			$viewData['teacher'] = Teacher::find($teacherId);
			$viewData['teacherId'] = $teacherId;
			$viewData['courseUnits'] = $viewData['teacher']->courseunit;
			return View::make('timetable')->with($viewData);
		});

		// 顯示教師排課編輯畫面
		Route::get('view_title/{titleId}/{teacherId}/{courseUnitId}', function($titleId, $teacherId, $courseUnitId)
		{
			$teacherList = ($titleId == 'all') ? Teacher::orderBy('teacher_name') : Teacher::where('title_id', '=', $titleId)->orderBy('teacher_name');
			$viewData['teacherList'] = $teacherList->get();
			$viewData['titleList'] = Title::orderBy('title_name')->get();
			$viewData['titleId'] = $titleId;
			$viewData['teacher'] = Teacher::find($teacherId);
			$viewData['teacherId'] = $teacherId;
			$viewData['courseUnits'] = $viewData['teacher']->courseunit;
			$viewData['courseUnit'] = Courseunit::find($courseUnitId);
			return View::make('timetable')->with($viewData);
		});

		// 執行更新排課設定
		Route::post('edit/{titleId}/{teacherId}/{courseUnitId}', function($titleId, $teacherId, $courseUnitId)
		{
			// 設定為編輯模式，方便驗證功能
			$validator = FormValidator::courseUnit(array_merge(Input::all(), array('mode' => 'edit' . $courseUnitId)));

			if ($validator->fails()) {
				return Redirect::to('/timetable/view_title/' . $titleId . '/' . $teacherId . '/' . $courseUnitId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				$data = Input::all();
				$courseUnit = Courseunit::find($courseUnitId);
				if ($courseUnit->update($data)) {
					$message = '更新排課設定完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('/timetable/view_title/' . $titleId . '/' . $teacherId)->with('message', $message);
			}
		});

		// 執行新增排課設定
		Route::post('/add/{titleId}/{teacherId}', function($titleId, $teacherId)
		{
			// 設定為新增模式，方便驗證功能
			$validator = FormValidator::courseUnit(array_merge(Input::all(), array('mode' => 'add')));

			if ($validator->fails()) {
				return Redirect::to('/timetable/view_title/' . $titleId . '/' . $teacherId)->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				$data = Input::all();

				if (Courseunit::create($data)) {
					$message = '新增完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('/timetable/view_title/' . $titleId . '/' . $teacherId)->with('message', $message);
			}
		});

		// 執行刪除排課設定
		Route::get('/delete/{titileId}/{courseUnitId}', function($titileId, $courseUnitId)
		{
			$courseUnit = Courseunit::find($courseUnitId);
			$url = '/timetable/view_title/' . $titileId . '/' . $courseUnit->teacher->teacher_id;
			$message = '刪除[' . $courseUnit->teacher->teacher_name . '：' . $courseUnit->classes->classes_name . '：' . $courseUnit->course->course_name . ']設定';
			$courseUnit->delete();
			return Redirect::to($url)->with('message', $message);
		});
	});

	/**
	 * 計算課表
	 */
	Route::group(array('prefix' => 'caculate', 'before' => 'auth'), function()
	{
		// 顯示計算課表表單
		Route::get('/', function()
		{
			return View::make('caculate');
		});

		// 執行計算課表
		Route::post('/', function()
		{
			$validator = FormValidator::caculate(Input::all());

			if ($validator->fails()) {
				return Redirect::to('caculate')->withInput()->withErrors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				$viewData['oldData'] = Input::all();
				$seedProgressHistory = Courseunit::caculate(Input::all());
				if ($seedProgressHistory[0] == 'error') {
					$viewData['message'] = '排課發生衝突，請檢查以下的排課內容';
					$viewData['errorTimetable'] = $seedProgressHistory[1];
				} else {
					$viewData['message'] = '排課完成';
					$viewData['seedProgressHistory'] = $seedProgressHistory;
				}

				return View::make('caculate')->with($viewData);
			}
		});
	});
}
