<?php
/**
 * 基礎Controller
 */
class Base_Controller extends Controller
{
	/**
	 * 設定layout
	 */
	public $layout = 'layout';

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}
