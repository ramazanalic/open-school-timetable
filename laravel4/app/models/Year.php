<?php
class Year extends Eloquent
{
	protected $table = 'year';
	protected $primaryKey = 'year_id';
	public $timestamps = false;
	protected $guarded = array('year_id');

	public function classes()
	{
		return $this->hasMany('Classes', 'year_id');
	}

}
?>