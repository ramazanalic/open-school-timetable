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

	/**
	 * 計算課表
	 */
	public static function caculate($seedCount, $extinction_time = 0)
	{
		// Data
		// $seed[0]['timetable'] =
		// $seed[0]['fitnesss'] =
		// $seed[0]['bestFitnesss'] =
		// $seed[0]['bestTimetable'] =

		$seedCount = $seedCount * 1;

		// 產生教室使用時間，提供排課使用
		$classroom = Classroom::all();
		foreach ($classroom as $classroomItem) {
			$GLOBALS['classroomCourseTime'][$classroomItem->classroom_id] = str_replace('1', $classroomItem->count, $classroomItem->course_time);
		}

		// 產生課表，速度、計算適應值
		$seed = self::_generateSeed($seedCount);
		dd($seed);

		// 進行粒子最佳化計算
		$withoutProgressCount = 0;
		$bestSeed = self::_getBestSeed($seed);
		while ($withoutProgressCount < 20) {
			// 計算新速度
			self::_updateV($seed, $bestSeed);

			// 依照速度更新課表排課
			self::_updateSeed($seed);

			// 計算適應值
			self::_cacualteFitness($seed);

			// 判斷是否改進
			if ($seed[$bestSeed]['bestFitnesss'] < $seed[self::_getBestSeed($seed)]['bestFitnesss']) {
				$bestSeed = self::_getBestSeed($seed);
				$withoutProgressCount = 0;
			} else {
				$withoutProgressCount++;
			}
		}

		file_put_contents(__DIR__ . '/../storage/result.json', json_encode($result));
	}

	/**
	 * 產生種子
	 */
	private static function _generateSeed($seedCount)
	{
		$seed = array();

		for ($seedCountI = 0; $seedCountI < $seedCount; $seedCountI++) {
			// 產生排課陣列
			$timetable = self::_getTimetableArray();

			// 更新課表排課
			$seed[$seedCountI]['timetable'] = self::_updateTimetable($timetable, true);
						
		}
		// 計算適應值
		
		
		return $seed;
	}

	/**
	 * 更新課表排課（需要重構排序過程）
	 */
	private static function _updateTimetable($timetable, $isNew = false)
	{
		// 取得教室使用時間
		$classroomCourseTime = $GLOBALS['classroomCourseTime'];

		// 重設可排課時間
		array_walk($timetable, function(&$item)
		{
			$item['available_course_time'] = $item['original_available_course_time'];
		});

		// 更新課表排課
		$result = array();
		while (count($timetable) > 0) {
			// 排序優先排課順序
			$timetable = self::_sortAvailableCourseTime($timetable);

			// 產生排課時間選擇陣列
			for ($position = 0, $coursePosition = array(); $position !== false; ) {
				$position = strpos($timetable[0]['available_course_time'], '1', $position);
				if ($position !== false) {
					$coursePosition[] = $position;
					$position++;
				}
			}

			// 檢查是否有衝突，可以排的時間被填滿，產生和那一個排課設定衝突的訊息（尚未實做）
			if (count($coursePosition) == 0) {
				print_r($result);
				echo '<hr>';
				print_r($timetable[0]);
				exit ;
			}

			if ($isNew == true) {
				// 隨機選擇排課時間
				$coursetime = $coursePosition[array_rand($coursePosition)];
				$timetable[0]['course_time'] = $coursetime;
			} else {
				// 依照速度變化排課時間
			}

			// 清除授課老師在同時段的可用的排課時間
			for ($i = 1; $i < count($timetable); $i++) {
				if ($timetable[$i]['teacher_id'] == $timetable[0]['teacher_id']) {
					$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], str_repeat('0', $timetable[0]['combination']), $coursetime, $timetable[0]['combination']);

				}
			}

			// 清除有上該班級老師可用的排課時間
			for ($i = 1; $i < count($timetable); $i++) {
				if ($timetable[$i]['classes_id'] == $timetable[0]['classes_id']) {
					$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], str_repeat('0', $timetable[0]['combination']), $coursetime, $timetable[0]['combination']);
				}
			}

			// 清除該班同天該老師可用的排課時間（有設定同天同班不排課）
			if ($timetable[0]['repeat'] == 0) {
				for ($i = 1; $i < count($timetable); $i++) {
					if ($timetable[$i]['classes_id'] == $timetable[0]['classes_id'] && $timetable[$i]['teacher_id'] == $timetable[0]['teacher_id']) {
						$left = floor($coursetime / 7);
						$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], str_repeat('0', 7), $left * 7, 7);
					}
				}
			}

			// 清除同教室同時段課程
			if ($timetable[0]['classroom_id'] != 0) {
				// 扣掉該教室該時段可用排課時間
				for ($combinationI = 0; $combinationI < $timetable[0]['combination']; $combinationI++) {
					$classroomCourseTimeCount = substr($classroomCourseTime[$timetable[0]['classroom_id']], $coursetime, 1) - 1;
					$classroomCourseTime[$timetable[0]['classroom_id']] = substr_replace($classroomCourseTime[$timetable[0]['classroom_id']], $classroomCourseTimeCount, $coursetime, 1);
					if ($classroomCourseTimeCount == 0) {
						for ($i = 1; $i < count($timetable); $i++) {
							if ($timetable[$i]['classroom_id'] == $timetable[0]['classroom_id']) {
								$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], '0', $coursetime, 1);
							}
						}
					}

				}
			}

			// 移動結果
			$result[] = $timetable[0];
			unset($timetable[0]);
		}

		return $result;
	}

	/**
	 * 排序排課優先順序
	 */
	private static function _sortAvailableCourseTime($timetable)
	{
		usort($timetable, function($a, $b)
		{
			return substr_count($a['available_course_time'], '1') > substr_count($b['available_course_time'], '1');
		});

		return $timetable;
	}

	/**
	 * 產生排課陣列
	 */
	private static function _getTimetableArray()
	{
		$courseunits = self::orderBy('course_unit_id')->get();
		$timetable_id = 0;
		$timetable = array();
		foreach ($courseunits as $courseunit) {
			$temp = $courseunit->toArray();
			$temp['course_name'] = $courseunit->course->course_name;
			$temp['teacher_name'] = $courseunit->teacher->teacher_name;
			$temp['classes_name'] = $courseunit->classes->classes_name;
			if ($temp['classroom_id'] != 0) {
				$temp['classroom_name'] = $courseunit->classroom->classroom_name;
			}
			$limit = unserialize($temp['course_unit_limit']);
			$temp['combination'] = $limit['combination'];
			$temp['repeat'] = $limit['repeat'];
			$temp['limit_course_time'] = ($limit['course_time']) ? $limit['course_time'] : str_repeat('1', 35);
			unset($temp['course_unit_limit']);
			unset($temp['course_unit_id']);
			$temp['total_count'] = $temp['count'];

			// 依照排課限制來產生可排課時間，並紀錄，提供之後課表移動使用
			$temp['available_course_time'] = $courseunit->classes->year->course_time & $temp['limit_course_time'];
			$temp['original_available_course_time'] = $temp['available_course_time'];

			// 依照教室可用時間來限制可排課時間
			if (is_object($courseunit->classroom)) {
				$temp['available_course_time'] &= $courseunit->classroom->course_time;
			}

			// 依照組合節數分解排課單元，產生速度
			while ($temp['count'] > 0) {
				$temp2 = $temp;
				unset($temp2['count']);

				if ($temp['count'] >= $temp['combination']) {
					$temp['count'] -= $temp['combination'];
					// 依照組合節數限制可排課時間
					if ($temp['combination'] == 2) {
						$temp2['available_course_time'] &= '11101101110110111011011101101110110';
					} else if ($temp['combination'] == 3) {
						$temp2['available_course_time'] &= '11001001100100110010011001001100100';
					}
				} else {
					$temp['count']--;
					$temp2['combination'] = 1;
				}

				$temp2['timetable_id'] = ++$timetable_id;
				$temp2['v'] = mt_rand(-1600, 1600) / 100;
				$timetable[] = $temp2;
			}
		}

		return $timetable;
	}

}
?>