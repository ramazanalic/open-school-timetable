<?php
class Classes extends Eloquent
{
	protected $table = 'classes';
	protected $primaryKey = 'classes_id';
	public $timestamps = false;
	protected $guarded = array('classes_id');

	public function year()
	{
		return $this->belongsTo('Year', 'year_id');
	}

}
?>