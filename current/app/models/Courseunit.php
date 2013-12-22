<?php
class Courseunit extends Eloquent
{
	protected $table = 'course_unit';
	protected $primaryKey = 'course_unit_id';
	public $timestamps = false;
	protected $guarded = array('course_unit_id');

	public function teacher()
	{
		return $this->hasMany('Teacher', 'teacher_id');
	}

	public function classes()
	{
		return $this->hasMany('Classes', 'classes_id');
	}

}
?>