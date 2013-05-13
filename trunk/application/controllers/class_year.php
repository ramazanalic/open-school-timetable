<?php
/**
 * 班級年級管理Controller
 */
class Class_Year_Controller extends Base_Controller
{
	/**
	 * index：顯示班級、年級首頁
	 */
	public function action_index()
	{
		$this->layout->nest('content', 'class_year.index');
	}

	/**
	 * 新增年級
	 */
	public function action_add_year()
	{
		if ($data = Input::all()) {
		}
	}

	/**
	 * 編輯年級
	 */
	public function action_edit_year()
	{
		if ($data = Input::all()) {
		}
	}

	/**
	 * 刪除年級
	 */
	public function action_delete_year()
	{
		if ($data = Input::all()) {
		}
	}

}
