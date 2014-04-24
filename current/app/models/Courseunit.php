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
		// 產生排課陣列
		$courseunits = self::orderBy('course_unit_id')->get();
		$timetable_id = 0;
		foreach ($courseunits as $courseunit) {
			$temp = $courseunit->toArray();
			$temp['course_name'] = $courseunit->course->course_name;
			$temp['teacher_name'] = $courseunit->teacher->teacher_name;
			$limit = unserialize($temp['course_unit_limit']);
			$temp['combination'] = $limit['combination'];
			$temp['repeat'] = $limit['repeat'];
			$temp['limit_course_time'] = ($limit['course_time']) ? $limit['course_time'] : str_repeat('1', 35);
			unset($temp['course_unit_limit']);
			unset($temp['course_unit_id']);
			$temp['available_count'] = $temp['count'];
			$temp['available_course_time'] = $courseunit->classes->year->course_time & $temp['limit_course_time'];

			// 分解排課單元
			while ($temp['count'] > 0) {
				$temp['count'] -= $temp['combination'];
				$temp2 = $temp;
				unset($temp2['count']);
				if ($temp['combination'] == 2) {
					$temp2['available_course_time'] &= '11101101110110111011011101101110110';
					$temp2['timetable_id'] = ++$timetable_id;
					$timetable[] = $temp2;
				} else {
					$temp2['timetable_id'] = ++$timetable_id;
					$timetable[] = $temp2;
				}

				if ($temp['count'] < $temp['combination'] && $temp['count'] > 0) {
					$temp['count']--;
					$temp['combination'] = 1;
					$temp['timetable_id'] = ++$timetable_id;
					$timetable[] = $temp;
				}

			}
		}

		print_r($timetable);

		// 產生課表（種子）
		foreach ($timetable as $course) {

		}

	}

}
?>