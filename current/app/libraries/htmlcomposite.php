<?php
class HtmlComposite
{
	public static function back($url)
	{
		return HTML::link(URL::to($url), '←返回', array('class' => 'back_link'));
	}

	public static function add($url, $itemName = NULL, $attributes = array())
	{
		if ($itemName != NULL) {
			return HTML::link(URL::to($url), $itemName . '&raquo;', $attributes);
		} else {
			return HTML::link(URL::to($url), '新增' . $itemName, array('class' => 'add_link'));
		}
	}

	public static function edit($url, $itemName = NULL, $attributes = array())
	{
		return HTML::link(URL::to($url), ($itemName == NULL) ? '編輯' : $itemName, array_merge(array('class' => 'edit_link'), $attributes));
	}

	public static function delete($url, $itemName = NULL)
	{
		return HTML::link(URL::to($url), ($itemName == NULL) ? '刪除' : $itemName, array('class' => 'delete_link'));
	}

	public static function messageBlock($message = NULL)
	{
		if (!$message) {
			$message = Session::get('message');
			if (!$message) {
				return '';
			}
		}
		return '<div class="messageBlock">' . $message . '</div>';
	}

}
?>