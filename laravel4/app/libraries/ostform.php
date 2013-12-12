<?php
/**
 * OST Form 類別
 */
class OstForm
{
	public static function text($name, $label, $attribs = array())
	{
		return '<tr><td class="label" id="' . $name .'_label">' . Form::label($name, $label) . '</td><td class="input_field" id="' . $name .'_input">' . Form::text($name, $value = NULL, $attribs) . '</td>' . self::_getInputError($name) . '</tr>';
	}

	public static function select($name, $label, $attribs = array(), $modelPair = NULL)
	{
		if (isset($attribs['valueArray'])) {
			$selectValueArray = $attribs['valueArray'];
		}

		if (is_array($modelPair)) {
			$modelName = $modelPair[0];
			$selectValueColumn = $modelPair[1];
			$selectNameColumn = $modelPair[2];
			$model = $modelName::orderBy($selectNameColumn)->get();
			foreach ($model as $item) {
				$selectValueArray[$item->$selectValueColumn] = $item->$selectNameColumn;
			}
		}

		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::select($name, $selectValueArray, $attribs) . '</td>' . self::_getInputError($name) . '</tr>';
	}

	public static function hidden($name, $attribs = array())
	{
		return '<tr><td colspan="2" style="display:none">' . Form::hidden($name, $value = NULL) . '</td></tr>';
	}

	public static function password($name, $label, $attribs = array())
	{
		return '<tr><td class="label">' . Form::label($name, $label) . '</td><td class="input_field">' . Form::password($name, $attribs) . '</td>' . self::_getInputError($name) . '</tr>';
	}

	public static function open($model = NULL, $url, $attributes = array())
	{
		$attributes = array_merge(array('url' => $url, 'class' => 'input_form'), $attributes);

		if (is_object($model)) {
			return Form::model($model, $attributes) . '<table class="form_table">';
		} else {
			return Form::open($attributes) . '<table class="form_table">';
		}
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

	private static function _getInputError($name)
	{
		if (Session::has('errors')) {
			if ($errorMessage = Session::get('errors')->first($name)) {
				return '<td class="input_error">*' . $errorMessage . '</td>';
			} else {
				return NULL;
			}
		}
	}

}
?>