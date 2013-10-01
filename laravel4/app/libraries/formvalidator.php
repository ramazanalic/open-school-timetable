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

}
?>