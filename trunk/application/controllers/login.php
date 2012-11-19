<?php
/**
 * 登入Controller
 */
class Login_Controller extends Base_Controller
{
	public function action_index()
	{
		$this -> layout -> nest('content', 'login.index');
	}

}
