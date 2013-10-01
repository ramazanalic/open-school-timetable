<?php
/**
 * OST Form 類別
 */
class OstForm
{
	private static $_errors;

	private static $_data;

	public static function setErrors($errors)
	{
		static::$_errors = $errors;
	}

	public static function setData($data)
	{
		static::$_data = $data;
	}

	public static function getData($name)
	{
		if (isset(static::$_data[$name])) {
			return static::$_data[$name];
		} else {
			return '';
		}
	}

	public static function text($name, $label, $attribs = array())
	{
		if (isset($attribs['value'])) {
			$value = $attribs['value'];
		} elseif (isset(static::$_data[$name])) {
			$value = static::$_data[$name];
		} else {
			$value = '';
		}

		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::text($name, $value, $attribs) . '</td>' . self::_getErrorBlock($name) . '</tr>';
	}

	public static function hidden($name, $attribs = array())
	{
		if (isset($attribs['value'])) {
			$value = $attribs['value'];
		} elseif (isset(static::$_data[$name])) {
			$value = static::$_data[$name];
		} else {
			$value = '';
		}

		return '<tr><td colspan="2" style="display:none">' . Form::hidden($name, $value) . '</td></tr>';
	}

	public static function password($name, $label, $attribs = array())
	{
		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::password($name, $attribs) . '</td>' . self::_getErrorBlock($name) . '</tr>';
	}

	public static function open($url, $attributes = array())
	{
		return Form::open(array_merge(array('url'=>$url, 'class' => 'input_form'), $attributes)) . '<table class="form_table">';
	}

	public static function submit($name)
	{
		return '<tr><td colspan="2" class="submit">' . Form::submit($name) .'</td></tr>';
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
		if (static::$_errors) {
			$errorMessage = static::$_errors->first($name);

			if ($errorMessage) {
				return '<td class="input_error">*' . $errorMessage . '</td>';
			} else {
				return NULL;
			}
		}
	}

}
?>