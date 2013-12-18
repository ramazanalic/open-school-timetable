<?php
class FormValidator
{
	/**
	 * 驗證教師
	 */
	public static function teacher($data, $passwordRequire = false)
	{
		$rules = array('teacher_name' => 'required', 'teacher_account' => 'required|alpha_num', 'classes_id' => 'required');
		$messages = array('alpha_num' => '請使用英文+數字', 'required' => '此欄位必填', 'confirmed' => '「密碼」和「確認密碼」必須相同');

		if ($passwordRequire == true) {
			$rules = array_merge($rules, array('teacher_password' => 'required|confirmed'));
		}

		if ($data)
			return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證教師職稱
	 */
	public static function title($data)
	{
		$rules = array('title_name' => 'required');
		$messages = array('year_name_required' => '請輸入職稱', );

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證年級
	 */
	public static function year($data)
	{
		$rules = array('year_name' => 'required');
		$messages = array('year_name_required' => '請輸入年級名稱', );

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證班級
	 */
	public static function classes($data)
	{
		$rules = array('classes_name' => 'required');
		$messages = array('classes_name_required' => '請輸入班級名稱', );

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證課程
	 */
	public static function course($data)
	{
		$rules = array('course_name' => 'required');
		$messages = array('course_name_required' => '請輸入課程名稱', );

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 驗證教室
	 */
	public static function classroom($data)
	{
		$rules = array('classroom_name' => 'required', 'max_course' => 'required|min:1|max:7');
		$messages = array('classroom_name_required' => '請輸入課程名稱', );

		return Validator::make($data, $rules, $messages);
	}

}
?>
