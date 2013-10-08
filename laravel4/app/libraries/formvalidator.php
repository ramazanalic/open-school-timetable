<?php
class FormValidator
{
	public static function teacher($data, $passwordRequire = false)
	{
		$rules = array('teacher_name' => 'required', 'teacher_account' => 'required|alpha_num');
		$messages = array('alpha_num' => '請使用英文+數字', 'required' => '此欄位必填', 'confirmed' => '「密碼」和「確認密碼」必須相同');

		if ($passwordRequire == true) {
			$rules = array_merge($rules, array('teacher_password' => 'required|confirmed'));
		}

		return Validator::make($data, $rules, $messages);
	}

	public static function year($data)
	{
		$rules = array('year_name' => 'required', );
		$messages = array('year_name_required' => '請輸入年級名稱', );

		return Validator::make($data, $rules, $messages);
	}

}
?>