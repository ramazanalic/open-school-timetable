<?php
class Classroom extends Eloquent
{
	protected $table = 'classroom';
	protected $primaryKey = 'classroom_id';
	public $timestamps = false;
	protected $guarded = array('classroom_id');
}
?>