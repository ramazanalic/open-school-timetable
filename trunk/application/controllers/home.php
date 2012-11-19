<?php
/**
 * 首頁Controller
 */
class Home_Controller extends Base_Controller
{
	public function action_index()
	{
		$this -> layout -> nest('content', 'home.index');
	}

}
