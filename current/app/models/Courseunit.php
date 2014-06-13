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
	public static function caculate($param)
	{
		// 設定執行時間
		set_time_limit(60);

		// 產生教室使用時間，提供排課使用
		$classroom = Classroom::all();
		foreach ($classroom as $classroomItem) {
			$GLOBALS['classroomCourseTime'][$classroomItem->classroom_id] = str_replace('1', $classroomItem->count, $classroomItem->course_time);
		}

		// 取得教師排課需求，計算適應值用
		$teacher = Teacher::where('course_time', '<>', str_repeat(0, 35))->get();
		foreach ($teacher as $teacherItem) {
			$GLOBALS['teacherRequire'][$teacherItem->teacher_id]['require'] = $teacherItem->course_time;
		}

		// 產生課表，速度、計算適應值
		$seed = self::_generateSeed($param['seedCount']);

		// 發生錯誤，回傳錯誤結果
		if ($seed[0] == 'error') {
			return $seed;
		}

		// 進行粒子最佳化計算
		$seedProgressHistory = array();
		$extinctionTimes = 1;
		while ($param['extinctionCount'] > 0) {
			$withoutProgressCount = 0;
			$bestSeed = self::_getBestSeed($seed);
			$seedProgressHistory[] = number_format($bestSeed['fitness'], 2, '.', '');

			while ($withoutProgressCount < $param['executeCount']) {
				// 更新種子速度
				self::_updateSeedV($seed, $bestSeed);

				// 依照速度更新課表排課、計算適應值
				$seed = self::_updateSeed($seed);

				// 取得新的全域最佳值
				$newBestSeed = self::_getBestSeed($seed);

				// 判斷是否改進
				if ($bestSeed['fitness'] < $newBestSeed['fitness']) {
					$bestSeed = $newBestSeed;
					$withoutProgressCount = 0;
					$seedProgressHistory[] = number_format($bestSeed['fitness'], 2, '.', '') . '＊';
				} else {
					$withoutProgressCount++;
					$seedProgressHistory[] = number_format($bestSeed['fitness'], 2, '.', '');
				}
			}

			// 進行判斷是否改進毀滅結果
			if (!isset($historyBestSeed) || $historyBestSeed['fitness'] < $bestSeed['fitness']) {
				$historyBestSeed = $bestSeed;
			}
			$seedProgressHistory[] = '<strong>（' . number_format($historyBestSeed['fitness'], 2, '.', '') . '）</strong>';
			$seedProgressHistory[] = 'Extinction ' . $extinctionTimes;
			$param['extinctionCount']--;
			$extinctionTimes++;
		}

		file_put_contents(storage_path() . '\result.json', json_encode($historyBestSeed['timetable']));
		return $seedProgressHistory;
	}

	/**
	 * 產生種子
	 */
	private static function _generateSeed($seedCount)
	{
		$seed = array();
		// 產生排課陣列
		$timetable = self::_getTimetableArray();
		for ($seedCountI = 0; $seedCountI < $seedCount; $seedCountI++) {
			// 建立速度
			array_walk($timetable, function(&$item)
			{
				$item['v'] = mt_rand(-1600, 1600) / 100;
			});

			// 更新課表排課，產生排課結果
			$seed[$seedCountI]['timetable'] = self::_updateTimetable($timetable, true);

			// 發生錯誤，回傳結果
			if ($seed[$seedCountI]['timetable'][0] == 'error') {
				return $seed[$seedCountI]['timetable'];
			}

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
		$seedCount = count($seed);
		for ($i = 0; $i < $seedCount; $i++) {
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
	 * 取得該課程的速度
	 */
	private static function _getTimetableV($timetable, $timetableId)
	{
		foreach ($timetable as $value) {
			if ($value['timetable_id'] == $timetableId) {
				return $value['v'];
			}
		}
	}

	/**
	 * 更新粒子速度
	 */
	private static function _updateSeedV(&$seed, $bestSeed)
	{
		// 參數設定
		$w = 0.5;
		$c1 = 2;
		$c2 = 2;
		$r1 = mt_rand(0, 100) / 100;
		$r2 = mt_rand(0, 100) / 100;

		foreach ($seed as &$seedItem) {
			$timetableId = 0;
			foreach ($seedItem['timetable'] as &$timetable) {
				$localBest = self::_getTimetableV($seedItem['timetable'], $timetableId);
				$globalBest = self::_getTimetableV($bestSeed['timetable'], $timetableId);
				$timetable['v'] = $w * $timetable['v'] + $c1 * $r1 * $localBest + $c2 * $r2 * $globalBest;
				$timetableId++;
			}
		}
	}

	/**
	 * 取得最佳種子
	 */
	private static function _getBestSeed($seed)
	{
		$bestFitness = 0;
		$bestKey = 0;
		$seedCount = count($seed);
		for ($i = 0; $i < $seedCount; $i++) {
			if ($seed[$i]['fitness'] > $bestFitness) {
				$bestFitness = $seed[$i]['fitness'];
				$bestKey = $i;
			}
		}

		return array(
			'timetable' => $seed[$bestKey]['timetable'],
			'fitness' => $bestFitness
		);
	}

	/**
	 * 計算課表適應值（需要修改）
	 */
	private static function _cacualteFitness($timetable)
	{
		// 取得教師設定需求
		$fitness = $GLOBALS['teacherRequire'];

		// 避免除以0
		if (count($fitness) == 0) {
			return 0;
		}

		// 取得排課時間、計算個別分數
		foreach ($fitness as $teacherId => &$fitnessItem) {
			$teacher = Teacher::find($teacherId);
			if ($teacher->classes_id != 0) {
				// 計算導師排課
				$fitnessItem['courseTime'] = $teacher->classes->year->course_time;

				// 扣掉科任課（不包含導師）
				foreach ($timetable as $course) {
					if ($course['classes_id'] == $teacher->classes_id && $course['teacher_id'] != $teacherId) {
						$fitnessItem['courseTime'] = substr_replace($fitnessItem['courseTime'], str_repeat('0', $course['combination']), $course['course_time'], $course['combination']);
					}
				}

				//計算分數
				$totalCourseTime = substr_count($fitnessItem['courseTime'], '1') + 1;
				$fitnessItem['score'] = substr_count($fitnessItem['courseTime'] & $fitnessItem['require'], '1') / $totalCourseTime;
			} else {
				// 計算科任排課
				$fitnessItem['courseTime'] = array();
				foreach ($timetable as $course) {
					if ($course['teacher_id'] == $teacherId) {
						for ($combination = 0; $combination < $course['combination']; $combination++) {
							$fitnessItem['courseTime'][] = $course['course_time'] + $combination;
						}

					}
				}

				$matchRequireTime = 0;
				foreach ($fitnessItem['courseTime'] as $courseTime) {
					if ($courseTime == strpos($fitnessItem['require'], '1', $courseTime)) {
						$matchRequireTime++;
					}
				}

				$totalCourseTime = count($fitnessItem['courseTime']) + 1;
				$fitnessItem['score'] = $matchRequireTime / $totalCourseTime;
			}
		}

		// 計算適應值
		$score = array();
		foreach ($fitness as $fitnessItem) {
			$score[] = $fitnessItem['score'];
		}

		// 低於平均的成員進行懲罰
		$mean = array_sum($score) / count($score);
		$finalScore = $mean;
		foreach ($score as $value) {
			if ($value < $mean) {
				$finalScore -= ($mean - $value) / 2;
			}
		}

		return $finalScore * 100;
	}

	/**
	 * 更新課表排課
	 */
	private static function _updateTimetable($timetable, $isNew = false)
	{
		// 取得教室使用時間
		$classroomCourseTime = $GLOBALS['classroomCourseTime'];

		// 重設可排課時間、數量
		array_walk($timetable, function(&$item)
		{
			$item['available_course_time'] = $item['original_available_course_time'];
			$item['available_course_time_count'] = substr_count($item['available_course_time'], '1');
		});

		// 更新課表排課
		$result = array();
		while (count($timetable) > 0) {
			// 排序優先排課順序
			usort($timetable, function($a, $b)
			{
				return $a['available_course_time_count'] - $b['available_course_time_count'];
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
				$error[0] = 'error';
				$error[1] = array_slice($timetable, 0, 5);
				return $error;
			}

			// 隨機選擇排課時間
			if ($isNew == true) {
				$timetable[0]['course_time'] = $coursePosition[array_rand($coursePosition)];
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
					$timetable[0]['course_time'] = $coursePosition[$key + $direction];
				}
			}

			$timetableCount = count($timetable);
			for ($i = 1; $i < $timetableCount; $i++) {
				// 清除授課老師在同時段的可用的排課時間
				if ($timetable[$i]['teacher_id'] == $timetable[0]['teacher_id']) {
					$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], str_repeat('0', $timetable[0]['combination']), $timetable[0]['course_time'], $timetable[0]['combination']);
				}

				// 清除有上該班級老師可用的排課時間
				if ($timetable[$i]['classes_id'] == $timetable[0]['classes_id']) {
					$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], str_repeat('0', $timetable[0]['combination']), $timetable[0]['course_time'], $timetable[0]['combination']);
				}

				// 清除該班同天該老師可用的排課時間（有設定同天同班不排課）
				if ($timetable[0]['repeat'] == 0) {
					if ($timetable[$i]['classes_id'] == $timetable[0]['classes_id'] && $timetable[$i]['teacher_id'] == $timetable[0]['teacher_id']) {
						$left = floor($timetable[0]['course_time'] / 7);
						$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], str_repeat('0', 7), $left * 7, 7);
					}
				}

				// 更新排課時間
				$timetable[$i]['available_course_time_count'] = substr_count($timetable[$i]['available_course_time'], '1');
			}

			// 清除同教室同時段課程
			if ($timetable[0]['classroom_id'] != 0) {
				// 扣掉該教室該時段可用排課時間
				for ($combinationI = 0; $combinationI < $timetable[0]['combination']; $combinationI++) {
					$classroomCourseTimeCount = substr($classroomCourseTime[$timetable[0]['classroom_id']], $timetable[0]['course_time'], 1) - 1;
					$classroomCourseTime[$timetable[0]['classroom_id']] = substr_replace($classroomCourseTime[$timetable[0]['classroom_id']], $classroomCourseTimeCount, $timetable[0]['course_time'], 1);
					if ($classroomCourseTimeCount == 0) {
						for ($i = 1; $i < $timetableCount; $i++) {
							if ($timetable[$i]['classroom_id'] == $timetable[0]['classroom_id']) {
								$timetable[$i]['available_course_time'] = substr_replace($timetable[$i]['available_course_time'], '0', $timetable[0]['course_time'], 1);
								$timetable[$i]['available_course_time_count'] = substr_count($timetable[$i]['available_course_time'], '1');
							}
						}
					}

				}
			}

			// 移動結果
			$result[] = array_shift($timetable);
		}

		return $result;
	}

	/**
	 * 產生排課陣列
	 */
	private static function _getTimetableArray()
	{
		$courseunits = self::orderBy('course_unit_id')->get();
		$timetable_id = -1;
		$timetable = array();
		foreach ($courseunits as $courseunit) {
			$temp = $courseunit->toArray();
			$temp['course_name'] = $courseunit->course->course_name;
			$temp['teacher_name'] = $courseunit->teacher->teacher_name;
			$temp['classes_name'] = $courseunit->classes->classes_name;

			$limit = unserialize($temp['course_unit_limit']);
			$temp['combination'] = $limit['combination'];
			$temp['repeat'] = $limit['repeat'];
			$temp['limit_course_time'] = ($limit['course_time']) ? $limit['course_time'] : str_repeat('1', 35);
			unset($temp['course_unit_limit']);
			$temp['total_count'] = $temp['count'];

			// 依照排課限制來產生可排課時間
			$temp['available_course_time'] = $courseunit->classes->year->course_time & $temp['limit_course_time'];

			// 依照教室可用時間來限制可排課時間
			if ($temp['classroom_id'] != 0) {
				$temp['classroom_name'] = $courseunit->classroom->classroom_name;
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

				// 紀錄目前的可排課時間，提供排課計算使用
				$temp2['original_available_course_time'] = $temp2['available_course_time'];

				$timetable[] = $temp2;
			}
		}

		return $timetable;
	}

}
?>