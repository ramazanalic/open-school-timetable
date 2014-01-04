<?php
class Course extends Eloquent
{
	protected $table = 'course';
	protected $primaryKey = 'course_id';
	public $timestamps = false;
	protected $guarded = array('course_id');

	public static function boot()
	{
		parent::boot();

		// 刪除相關排課設定
		static::deleting(function($course)
		{
			$courseUnit = Courseunit::where('course_id', '=', $course->course_id)->delete();
		});
	}

}
?>