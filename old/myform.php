<?php
	/**
	 *
	 */
	class MyForm
	{

		static function text($name)
		{
			return Form::label($name, 'Label') . Form::text($name);

		}

	}
?>