<?php
/**
 * 帳號密碼管理Controller
 */
class Account_Controller extends Base_Controller
{
	/**
	 * index：顯示教師列表
	 */
	public function action_index()
	{
		$viewData['teacherList'] = Teacher::all();
		$this->layout->nest('content', 'account.index', $viewData);
	}

	/**
	 * add：新增教師
	 */
	public function action_add()
	{
		if ($data = Input::all()) {
			$validator = $this->_validateTeacher($data);
			if ($validator->fails()) {
				return Redirect::to('account/add')->with_input()->with_errors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				if ($techer = Teacher::create($this->_collateTeacher($data))) {
					$message = '新增教師"' . $data['name'] . '"完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('account')->with('message', $message);
			}

		}

		$this->layout->nest('content', 'account.add');
	}

	/**
	 * edit：編輯教師
	 */
	public function action_edit($id)
	{
		echo $id;
		exit;
		$this->layout->nest('content', 'account.edit');
	}

	/**
	 * edit：刪除教師
	 */
	public function action_delete($id)
	{
		$this->layout->nest('content', 'account.edit');
	}

	/**
	 * 驗證教師資料
	 */
	private function _validateTeacher($data, $passwordRequire = true)
	{
		$rules = array(
			'name' => 'required',
			'account' => 'required|alpha_num'
		);

		if ($passwordRequire == true) {
			$rules = array_merge($rules, array('password' => 'required|confirmed'));
		}

		$messages = array(
			'name_required' => '請輸入姓名',
			'account_required' => '請輸入帳號',
			'account_alpha_num' => '帳號請使用英文和數字',
			'password_required' => '密碼不能空白',
			'password_confirmed' => '請確定確認密碼和密碼相同'
		);

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 整理教師資料，提供資料庫寫入
	 */
	private function _collateTeacher($data)
	{
		return array(
			'teacher_name' => $data['name'],
			'teacher_account' => $data['account'],
			'teacher_password_hash' => Hash::make($data['password'])
		);
	}

}
