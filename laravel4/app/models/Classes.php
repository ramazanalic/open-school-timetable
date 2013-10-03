<?php
class Classes extends Eloquent
{
	protected $table = 'classes';
	protected $key = 'classes_id';
	public $timestamps = false;
	protected $guarded = array('classes_id');

	public function year()
	{
		return $this->belongs_to('Year', year_id);
	}

}
?>