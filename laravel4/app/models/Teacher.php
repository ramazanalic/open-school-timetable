<?php
class Teacher extends Eloquent
{
	protected $table = 'teacher';
	protected $primaryKey = 'teacher_id';
	public $timestamps = false;
	protected $guarded = array('teacher_id');
}
?>