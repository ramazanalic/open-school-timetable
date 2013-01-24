<?php
class HtmlComposite
{
	public static function back($url)
	{
		return '<a class="back" href="' . URL::to($url) .'">←返回</a>'; 
	}
}
?>