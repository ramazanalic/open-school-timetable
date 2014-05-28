<?php
class Helper
{
	public static function back($url)
	{
		return link_to($url, '←返回', array('class' => 'back_link'));
	}

	public static function add($url, $itemName = NULL, $attributes = array())
	{
		return link_to($url, ($itemName == NULL) ? '新增&raquo;' : $itemName . '&raquo;', array_merge(array('class' => 'add_link'), $attributes));
	}

	public static function edit($url, $itemName = NULL, $attributes = array())
	{
		return link_to($url, ($itemName == NULL) ? '編輯' : $itemName, array_merge(array('class' => 'edit_link'), $attributes));
	}

	public static function delete($url, $itemName = NULL, $attributes = array())
	{
		return link_to($url, ($itemName == NULL) ? '刪除' : $itemName, array_merge(array('class' => 'delete_link'), $attributes));
	}

	public static function message($message = NULL, $append = false)
	{
		if ($message && $append == true) {
			$message = Session::get('message') . '。' . $message;
		} elseif (!$message) {
			$message = Session::get('message');
		}

		if (!$message) {
			return '';
		}

		if (mb_strlen($message) > 10) {
			return '<div class="messageBlock" style="width:' . (mb_strlen($message) + 1) . 'em">' . $message . '</div>';
		} else {
			return '<div class="messageBlock">' . $message . '</div>';
		}
	}

}
?>