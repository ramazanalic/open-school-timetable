<?php
/**
 * FormList 類別
 */
class FormList
{
	public static function text($name, $label, $attribs = array())
	{
		return '<li id="' . $name . '_label">' . Form::label($name, $label) . '<div id="' . $name . '_input" class="input_field">' . Form::text($name, $value = NULL, $attribs) . self::_getInputError($name) . '</div></li>';
	}

	public static function select($name, $label, $attribs = array(), $modelPair = NULL, $wrapper = 1)
	{
		if (isset($attribs['valueArray'])) {
			$selectValueArray = $attribs['valueArray'];
			unset($attribs['valueArray']);
		}

		if (isset($attribs['range'])) {
			for ($i = $attribs['range'][0]; $i <= $attribs['range'][1]; $i++) {
				$selectValueArray[$i] = $i;
			}
			unset($attribs['range']);
		}

		$value = (isset($attribs['value'])) ? (string)$attribs['value'] : null;

		if (is_array($modelPair)) {
			$modelName = $modelPair[0];
			$selectValueColumn = $modelPair[1];
			$selectNameColumn = $modelPair[2];
			$model = $modelName::orderBy($selectNameColumn)->get();
			foreach ($model as $item) {
				$selectValueArray[$item->$selectValueColumn] = $item->$selectNameColumn;
			}
		}

		$selectOutput = Form::select($name, $selectValueArray, $value, $attribs) . self::_getInputError($name);
		if ($wrapper == 1) {
			return '<li id="' . $name . '_label">' . Form::label($name, $label) . '<div id="' . $name . '_input" class="input_field">' . $selectOutput . '</div></li>';
		} else {
			return $selectOutput;
		}

	}

	public static function hidden($name, $value = NULL)
	{			
		return '<li style="display:none">' . Form::hidden($name, $value) . '</li>';
	}

	public static function password($name, $label, $attribs = array())
	{
		return '<li id="' . $name . '_label">' . Form::label($name, $label) . '<div id="' . $name . '_input" class="input_field">' . Form::password($name, $attribs) . self::_getInputError($name) . '</div></li>';
	}

	public static function checkbox($name, $label, $value, $checked = null)
	{
		return '<li id="' . $name . '_label">' . '<div id="' . $name . '_input" class="input_field input_checkbok">' . Form::checkbox($name, $value, $checked, array('id' => $name)) .'</div>' . Form::label($name, $label) . '</li>';
	}

	public static function open($model = NULL, $url, $attributes = array())
	{
		$attributes = array_merge(array(
			'url' => $url,
			'class' => 'input_form'
		), $attributes);

		if (is_object($model)) {
			return Form::model($model, $attributes) . '<ul class="form_list">';
		} else {
			return Form::open($attributes) . '<ul class="form_list">';
		}
	}

	public static function submit($name)
	{
		return '<li class="submit">' . Form::submit($name) . '</li>';
	}

	public static function description($description)
	{
		return '<li class="description">' . $description . '</li>';
	}

	public static function close()
	{
		return '</ul>' . Form::close();
	}

	private static function _getInputError($name)
	{
		if (Session::has('errors')) {
			if ($errorMessage = Session::get('errors')->first($name)) {
				return '<span class="input_error">*' . $errorMessage . '</span>';
			} else {
				return NULL;
			}
		}
	}

}
?>