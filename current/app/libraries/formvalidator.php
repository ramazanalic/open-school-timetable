<?php
class FormValidator
{
	/**
	 * 驗證教師
	 */
	public static function teacher($data, $passwordRequire = false)
	{
		$rules = array(
			'teacher_name' => 'required',
			'teacher_account' => 'required|alpha_num',
			'classes_id' => 'required|integer',
			'teacher_privilege' => 'required|in:16,2',
			'course_time' => 'numeric'
		);
		$messages = array(
			'alpha_num' => '請使用英文+數字',
			'required' => '此欄位必填',
			'confirmed' => '「密碼」和「確認密碼」必須相同'
		);

		if ($passwordRequire == true) {
			$rules = array_merge($rules, array('teacher_password' => 'required|confirmed'));
		}

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證教師需求
	 */
	public static function teacherRequire($data)
	{
		$rules = array('course_time' => 'required|required');
		$messages = array('course_time_required' => '請設定排課需求');
		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證密碼
	 */
	public static function password($data)
	{
		// 檢查密碼
		Validator::extend('check_password', function($attribute, $data, $parameters)
		{
			if (Hash::check($data, Auth::user()->teacher_password_hash)) {
				return true;
			} else {
				return false;
			}
		});

		$rules = array(
			'old_teacher_password' => 'required|check_password',
			'teacher_password' => 'required|confirmed'
		);
		$messages = array(
			'required' => '此欄位必填',
			'confirmed' => '「密碼」和「確認密碼」必須相同',
			'check_password' => '舊密碼不正確'
		);

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證教師職稱
	 */
	public static function title($data)
	{
		$rules = array('title_name' => 'required');
		$messages = array('title_name_required' => '請輸入職稱');

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證年級
	 */
	public static function year($data)
	{
		$rules = array('year_name' => 'required');
		$messages = array('year_name_required' => '請輸入年級名稱');

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證班級
	 */
	public static function classes($data)
	{
		$rules = array('classes_name' => 'required');
		$messages = array('classes_name_required' => '請輸入班級名稱');

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證課程
	 */
	public static function course($data)
	{
		$rules = array('course_name' => 'required');
		$messages = array('course_name_required' => '請輸入課程名稱');

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證教室
	 */
	public static function classroom($data)
	{
		$rules = array(
			'classroom_name' => 'required',
			'count' => 'required|min:1|max:10'
		);

		$messages = array('classroom_name_required' => '請輸入課程名稱');

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證排課單元
	 */
	public static function courseUnit($data)
	{
		// 衝突檢查
		Validator::extend('conflict', function($attribute, $data, $parameters)
		{
			/* Array參考
			 [0][_token] => Yvw1pYLjZIm1BaiPJ1yTYKbs3Nl8T9zfkLesI0Bh
			 [1][classes_id] => 4
			 [2][course_id] => 9
			 [3][count] => 1
			 [4][classroom_id] => 0
			 [5][combination] => 1
			 [6][repeat] => 0
			 [7][limit_course_time] => 1
			 [8][course_time] => 10000001000000110000011000001100000
			 [9][teacher_id] => 19
			 */

			// 處理變數
			list(, $classes_id, $course_id, $count, $classroom_id, $combination, $repeat) = $parameters;
			$mode = end($parameters);
			$teacher_id = prev($parameters);

			if (substr($mode, 0, 4) == 'edit') {
				$courseUnitId = intval(substr($mode, 4));
				$mode = substr($mode, 0, 4);
			}

			if ($parameters[7] == 1) {
				$course_time = $parameters[8];
			} else {
				$course_time = 0;
			}

			// 限制排課時間少於設定節數
			if (substr_count($course_time, '1') < $count && substr_count($course_time, '1') != 0) {
				Session::flash('conflictError', '限制排課時間（共' . substr_count($course_time, '1') . '節）少於設定節數（' . $count . '）');
				return false;
			}

			// 組合節數大於設定節數
			if ($combination > $count) {
				Session::flash('conflictError', '組合節數（' . $combination . '）大於設定節數（' . $count . '）');
				return false;
			}

			// 同天不排課，但是（排課節數 / 組合節數）超過5
			if ($repeat == 0 && ($count / $combination > 5)) {
				Session::flash('conflictError', '同天同班不重複排課，但是（排課節數 / 組合節數）超過5');
				return false;
			}

			// 教室可排課時間不足
			if ($classroom_id != 0) {
				$courseCount = 0;
				$courseUnitUseClassroom = array();
				foreach (Courseunit::where('classroom_id', '=', $classroom_id)->get() as $courseunit) {
					$courseCount += $courseunit->count;
					$courseUnitUseClassroom[] = $courseunit->teacher->teacher_name;
				}

				$classroom = Classroom::find($classroom_id);

				// 檢查是否為編輯模式
				if ($mode == 'edit') {
					$courseUnit = Courseunit::find($courseUnitId);
					if ($courseUnit->classroom_id == $classroom_id) {
						$courseCount -= $courseUnit->count;
					}
				}

				if (substr_count($classroom->course_time, '1') * $classroom->count - $courseCount - $count < 0) {
					$teacherList = implode($courseUnitUseClassroom, ',');
					Session::flash('conflictError', '教室《' . $classroom->classroom_name . '》可排課時間不足，目前有使用的老師：' . $teacherList);
					return false;
				}
			}

			// 班級可排節數已滿
			$classesCourseCount = 0;
			foreach (Courseunit::where('classes_id', '=', $classes_id)->get() as $courseunit) {
				$classesCourseCount += $courseunit->count;
			}

			// 編輯模式
			if ($mode == 'edit') {
				$courseUnit = Courseunit::find($courseUnitId);
				if ($courseUnit->classes_id == $classes_id) {
					$classesCourseCount -= $courseUnit->count;
				}
			}

			$classes = Classes::find($classes_id);
			if (substr_count($classes->year->course_time, '1') - $classesCourseCount - $count < 0) {
				Session::flash('conflictError', '班級《' . $classes->classes_name . '》可排課時間不足');
				return false;
			};

			return true;
		});

		$rules = array(
			'teacher_id' => 'required|integer',
			'course_id' => 'required|integer',
			'classroom_id' => 'required|integer',
			'count' => 'required|integer',
			'combination' => 'required|integer|conflict:' . implode(',', $data),
			'repeat' => 'required|integer',
			'course_time' => 'numeric'
		);

		$messages = array(
			'required' => '此欄位必填',
			'integer' => '必需為數字',
			'conflict' => '排課衝突'
		);

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證計算課表參數
	 */
	public static function caculate($data)
	{
		$rules = array(
			'seedCount' => 'required|integer',
			'extinction_time' => 'integer'
		);

		$messages = array(
			'required' => '此欄位必填',
			'integer' => '參數錯誤'
		);

		return Validator::make($data, $rules, $messages);
	}

}
?>
