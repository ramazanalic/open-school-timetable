<?php
/**
 * 班級年級管理Controller
 */
class Class_Year_Controller extends Base_Controller
{
	private $_viewData;

	public function __construct()
	{
		$this->_viewData['yearList'] = Year::order_by('year_name')->get();
		parent::__construct();
	}

	/**
	 * index：顯示班級、年級首頁
	 */
	public function action_index()
	{
		$this->layout->nest('content', 'class_year.index', $this->_viewData);
	}

	/**
	 * 新增年級
	 */
	public function action_add_year()
	{
		if ($data = Input::all()) {
			$validator = $this->_validateYear($data);
			if ($validator->fails()) {
				return Redirect::to('class_year/add_year')->with_input()->with_errors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				if ($techer = Year::create($this->_handleYearData($data))) {
					$message = '新增年級《' . $data['year_name'] . '》完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('class_year')->with('message', $message);
			}
		}

		$this->_viewData['showYearForm'] = true;
		$this->layout->nest('content', 'class_year.index', $this->_viewData);
	}

	/**
	 * 顯示年級中的班級、年級編輯選單
	 */
	public function action_view_year($id)
	{
		$year = Year::find($id);
		$this->_viewData['showYearForm'] = true;
		$this->_viewData['editYearForm'] = true;
		$this->_viewData['year'] = $year;
		$this->layout->nest('content', 'class_year.index', $this->_viewData);
	}

	/**
	 * 執行編輯年級
	 */
	public function action_edit_year($id)
	{
		if ($data = Input::all()) {
			$validator = $this->_validateYear($data);
			if ($validator->fails()) {
				return Redirect::to('class_year/view_year/' . $id)->with_input()->with_errors($validator)->with('message', '輸入錯誤，請檢查');
			} else {
				if ($year = Year::update($id, $this->_handleYearData($data))) {
					$message = '更新年級《' . $data['year_name'] . '》完成';
				} else {
					$message = '資料寫入錯誤';
				}

				return Redirect::to('class_year/view_year/' . $id)->with('message', $message);
			}
		}
	}

	/**
	 * 刪除年級
	 */
	public function action_delete_year($id)
	{
		$year = Year::find($id);
		$year_name = $year->year_name;
		$year->delete();
		return Redirect::to('class_year/')->with('message', '刪除《' . $year_name . '》完成');
	}

	/**
	 * 驗證年級資料
	 */
	private function _validateYear($data)
	{
		$rules = array('year_name' => 'required', );

		$messages = array('year_name_required' => '請輸入年級名稱', );

		return Validator::make($data, $rules, $messages);
	}

	/**
	 * 整理年級資料，提供資料庫寫入
	 */
	private function _handleYearData($data)
	{
		return $data;
	}

}
