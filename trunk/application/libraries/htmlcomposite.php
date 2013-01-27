<?php
class HtmlComposite
{
	public static function back($url)
	{
		return HTML::link(URL::to($url), '←返回', array('class' => 'back'));
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