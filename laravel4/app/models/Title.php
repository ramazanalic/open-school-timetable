<?php
class Title extends Eloquent
{
	protected $table = 'title';
	protected $primaryKey = 'title_id';
	public $timestamps = false;
	protected $guarded = array('title_id');

	public function teacher()
	{
		return $this->hasMany('Teacher', 'title_id');
	}

}
?>