<?php
class Year extends Eloquent
{
	public static $table = 'year';
	public static $key = 'year_id';
	public static $timestamps = false;

	public function classes()
	{
		return $this->has_many('Classes', 'year_id');
	}

}
?>