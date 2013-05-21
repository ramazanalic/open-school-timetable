<?php
class HtmlComposite
{
	public static function back($url)
	{
		return HTML::link(URL::to($url), '←返回', array('class' => 'back_link'));
	}

	public static function add($url, $itemName = NULL, $attributes = NULL)
	{
		if ($itemName != NULL) {
			return HTML::link(URL::to($url), $itemName . '&raquo;', $attributes);
		} else {
			return HTML::link(URL::to($url), '新增' . $itemName . '&raquo;', array('class' => 'add_link'));
		}
	}

	public static function edit($url)
	{
		return HTML::link(URL::to($url), '編輯', array('class' => 'edit_link'));
	}

	public static function delete($url)
	{
		return HTML::link(URL::to($url), '刪除', array('class' => 'delete_link'));
	}

	public static function messageBlock($message = NULL)
	{
		if (!$message) {
			$message = Session::get('message');
			if (!$message) {
				return '';
			}
		}
		return '<div class="messageBlock">＃&nbsp;' . $message . '</div>';
	}

}
?>