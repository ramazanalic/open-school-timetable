<?php
class Course extends Eloquent
{
	protected $table = 'course';
	protected $primaryKey = 'course_id';
	public $timestamps = false;
	protected $guarded = array('course_id');
}
?>