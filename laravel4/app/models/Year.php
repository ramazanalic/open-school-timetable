<?php
class Year extends Eloquent
{
	protected $table = 'year';
	protected $key = 'year_id';
	public $timestamps = false;
	protected $guarded = array('year_id');

	public function classes()
	{
		return $this->has_many('Classes', 'year_id');
	}

}
?>