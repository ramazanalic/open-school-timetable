<?php
class Classes extends Eloquent
{
	public static $table = 'classes';
	public static $key = 'classes_id';
	public static $timestamps = false;

	public function year()
	{
		return $this->belongs_to('Year', year_id);
	}

}
?>