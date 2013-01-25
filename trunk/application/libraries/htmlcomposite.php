<?php
class HtmlComposite
{
    public static function back($url)
    {
        return HTML::link(URL::to($url), '←返回', array('class' => 'back'));
    }

}
?>