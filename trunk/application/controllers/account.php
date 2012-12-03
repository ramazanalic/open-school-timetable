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
		$this -> layout -> nest('content', 'account.index');
	}
	
	/**
	 * add：新增教師
	 */
	public function action_add()
	{
		$this -> layout -> nest('content', 'account.add');
	}
	
	/**
	 * edit：編輯教師
	 */
	public function action_edit()
	{
		$this -> layout -> nest('content', 'account.edit');
	}

}
