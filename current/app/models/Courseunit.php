<?php
class Courseunit extends Eloquent
{
	protected $table = 'course_unit';
	protected $primaryKey = 'course_unit_id';
	public $timestamps = false;
	protected $guarded = array('course_unit_id');

	public function teacher()
	{
		return $this->belongsTo('Teacher', 'teacher_id');
	}

	public function classes()
	{
		return $this->belongsTo('Classes', 'classes_id');
	}

	public function course()
	{
		return $this->belongsTo('Course', 'course_id');
	}

	public function classroom()
	{
		return $this->belongsTo('Classroom', 'classroom_id');
	}

	public static function boot()
	{
		parent::boot();

		static::saving(function($data)
		{
			// 處理排課限制
			if (isset($data->combination)) {
				$limit['combination'] = $data->combination;
			}

			if (isset($data->repeat)) {
				$limit['repeat'] = $data->repeat;
			}

			if ($data->limit_course_time == 1 && $data->course_time != 0) {
				$limit['limit_course_time'] = $data->limit_course_time;
				$limit['course_time'] = $data->course_time;
			} else {
				$limit['limit_course_time'] = 0;
				$limit['course_time'] = null;
			}

			unset($data->combination, $data->repeat, $data->limit_course_time, $data->course_time);
			$data->course_unit_limit = serialize($limit);
		});
	}

	public static function caculate($time, $extinction_time = 0)
	{
		// 產生排課順位陣列
		$courseunits = self::orderBy('course_unit_id')->get();
		foreach ($courseunits as $courseunit) {
			$temp =  $courseunit->toArray();
			$limit = unserialize($temp['course_unit_limit']);
			$temp['combination'] = $limit['combination'];
			$temp['repeat'] = $limit['repeat'];			
			$temp['limit_course_time'] = $limit['course_time'];
			unset($temp['course_unit_limit']);
			// available_course_time
			// available_course_count
			$timetable[] = $temp;
		}
		
		print_r($timetable);
	}

}
?>