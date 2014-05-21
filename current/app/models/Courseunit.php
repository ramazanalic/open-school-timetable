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
		// $seed[0]['timetable']
		// $seed[0]['fitness']
		// $seed[0]['bestFitnesss']
		// $seed[0]['bestTimetable']
		// $bestSeed['timetable']
		// $bestSeed['fitness']

		$seedCount = $seedCount * 1;

		// 產生教室使用時間，提供排課使用
		$classroom = Classroom::all();
		foreach ($classroom as $classroomItem) {
			$GLOBALS['classroomCourseTime'][$classroomItem->classroom_id] = str_replace('1', $classroomItem->count, $classroomItem->course_time);
		}

		// 產生課表，速度、計算適應值
		$seed = self::_generateSeed($seedCount);

		// 進行粒子最佳化計算
		$withoutProgressCount = 0;
		$bestSeed = self::_getBestSeed($seed);

		while ($withoutProgressCount < 20) {
			// 更新種子速度
			// self::_updateV($seed, $bestSeed);

			// 依照速度更新課表排課、計算適應值
			$seed = self::_updateSeed($seed);

			// 取得新的全域最佳值
			$newBestSeed = self::_getBestSeed($seed);

			// 判斷是否改進
			if ($bestSeed['fitness'] < $newBestSeed['fitness']) {
				$bestSeed = $newBestSeed;
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
		// 產生排課陣列
		$timetable = self::_getTimetableArray();
		for ($seedCountI = 0; $seedCountI < $seedCount + 20; $seedCountI++) {
			// 建立速度
			array_walk($timetable, function(&$item)
			{
				$item['v'] = mt_rand(-1600, 1600) / 100;
			});

			// 更新課表排課，產生排課結果
			$seed[$seedCountI]['timetable'] = self::_updateTimetable($timetable, true);

			// 計算適應值
			$seed[$seedCountI]['fitness'] = self::_cacualteFitness($seed[$seedCountI]['timetable']);

			// 建立種子最佳適應值
			$seed[$seedCountI]['bestTimetable'] = $seed[$seedCountI]['timetable'];
			$seed[$seedCountI]['bestFitness'] = $seed[$seedCountI]['fitness'];
		}

		return $seed;
	}

	/**
	 * 更新種子排課、適應值
	 */
	private static function _updateSeed($seed)
	{
		for ($i = 0; $i < count($seed); $i++) {
			$seed[$i]['timetable'] = self::_updateTimetable($seed[$i]['timetable']);
			$seed[$i]['fitness'] = self::_cacualteFitness($seed[$i]['timetable']);

			// 更新種子最佳適應值
			if ($seed[$i]['fitness'] > $seed[$i]['bestFitness']) {
				$seed[$i]['bestTimetable'] = $seed[$i]['timetable'];
				$seed[$i]['bestFitness'] = $seed[$i]['fitness'];
			}
		}

		return $seed;
	}

	/**
	 * 取得最佳種子
	 */
	private static function _getBestSeed($seed)
	{
		$bestFitness = 0;
		$bestKey = 0;
		for ($i = 0; $i < count($seed); $i++) {
			if ($seed[$i]['fitness'] > $bestFitness) {
				$bestFitness = $seed[$i]['fitness'];
				$bestKey = $i;
			}
		}

		return array(
			'timetable' => $seed[$bestKey],
			'fitness' => $bestFitness
		);
	}

	/**
	 * 計算課表適應值
	 */
	private static function _cacualteFitness($timetable)
	{
		// 取得教師排課時間、排課需求時間
		$fitness = array();
		while (count($timetable) > 0) {
			$first = array_values($timetable);
			$teacherId = $first[0]['teacher_id'];
			foreach ($timetable as $key => $course) {
				if ($course['teacher_id'] == $teacherId) {
					$fitness[$teacherId]['courseTime'][] = $course['course_time'];
					unset($timetable[$key]);
				}
			}
			$fitness[$teacherId]['require'] = Teacher::find($teacherId)->course_time;

			// 計算個別教師分數
			if (intval($fitness[$teacherId]['require']) > 0) {
				$totalCourseTime = count($fitness[$teacherId]['courseTime']);
				$matchCourseTime = 0;
				foreach ($fitness[$teacherId]['courseTime'] as $postion) {
					if (substr($fitness[$teacherId]['require'], $postion, 1) == '1') {
						$matchCourseTime++;
					}
				}
				$fitness[$teacherId]['score'] = $matchCourseTime / $totalCourseTime * 100;
			} else {
				$fitness[$teacherId]['score'] = null;
			}
		}

		// 計算適應值
		$result = 0;
		$resultCount = 1;
		foreach ($fitness as $teacher) {
			if ($teacher['score'] != null) {
				$result += $teacher['score'];
				$resultCount++;
			}
		}

		$result /= $resultCount;
		// 有成員太低分進行懲罰（尚未實做）
		return $result;
	}

	/**
	 * 更新課表排課
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
			usort($timetable, function($a, $b)
			{
				$aCourseTimeCount = substr_count($a['available_course_time'], '1');
				$bCourseTimeCount = substr_count($b['available_course_time'], '1');

				if ($aCourseTimeCount > $bCourseTimeCount) {
					return 1;
				} elseif ($aCourseTimeCount < $bCourseTimeCount) {
					return -1;
				} else {
					return 0;
				}
			});

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

			// 隨機選擇排課時間
			if ($isNew == true) {
				$coursetime = $coursePosition[array_rand($coursePosition)];
			} else {
				// 速度變化排課時間，判斷尋找方向
				if ($timetable[0]['v'] > 0) {
					$direction = 1;
				} elseif ($timetable[0]['v'] < 0) {
					$direction = -1;
				} else {
					$direction = 0;
				}

				// 尋找最近方向的可排課時間
				$key = array_search($timetable[0]['course_time'], $coursePosition);
				if (isset($coursePosition[$key + $direction])) {
					$coursetime = $coursePosition[$key + $direction];
				} else {
					$coursetime = $timetable[0]['course_time'];
				}

			}

			$timetable[0]['course_time'] = $coursetime;

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

				// 建立排課ID，提供速度計算使用
				$temp2['timetable_id'] = ++$timetable_id;
				$timetable[] = $temp2;
			}
		}

		return $timetable;
	}

}
?>