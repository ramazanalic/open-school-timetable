<?php
/**
 * OST Form 類別
 */
class OstForm
{
	private static $_errors;

	public static function setErrors($errors)
	{
		static::$_errors = $errors;
	}

	public static function text($name, $label, $value = NULL, $attribs = array())
	{
		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::text($name, $value, $attribs) . '</td>' . self::_getErrorBlock($name) . '</tr>';
	}

	public static function hidden($name, $value)
	{
		return '<tr><td style="display:none">' . Form::hidden($name, $value) . '</td></tr>';
	}

	public static function password($name, $label, $attribs = array())
	{
		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::password($name, $attribs) . '</td>' . self::_getErrorBlock($name) . '</tr>';
	}

	public static function open($action = NULL, $method = 'POST', $attributes = array(), $https = NULL)
	{
		return Form::open($action, $method, array_merge($attributes, array('class' => 'input_form')), $https) . '<table class="form_table">';
	}

	public static function submit($name)
	{
		return '<tr><td colspan="2" class="submit">' . Form::submit($name) . '</td></tr>';
	}

	public static function description($description)
	{
		return '<caption>' . $description . '</caption>';
	}

	public static function close()
	{
		return '</table>' . Form::close();
	}

	private static function _getErrorBlock($name)
	{
		$errorMessage = static::$_errors->first($name);

		if ($errorMessage) {
			return '<td class="input_error">*' . $errorMessage . '</td>';
		} else {
			return NULL;
		}
	}

}
?>