<?php
/**
 * OST Form 類別
 */
class OSTForm extends Form
{
	public static function textLine($name, $label, $value = NULL, $attribs = array())
	{
		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::text($name, $value, $attribs) . '</td></tr>';
	}

	public static function open($action = NULL, $method = 'POST', $attributes = array(), $https = NULL)
	{
		return Form::open($action, $method, $attributes, $https) . '<table class="form_table">';
	}

	public static function close()
	{
		return '</table>' . Form::close();
	}

}
?>