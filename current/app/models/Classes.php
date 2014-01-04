<?php
class Classes extends Eloquent
{
	protected $table = 'classes';
	protected $primaryKey = 'classes_id';
	public $timestamps = false;
	protected $guarded = array('classes_id');
	public static $last_classes_id;

	public static function boot()
	{
		parent::boot();

		static::saved(function($classes)
		{
			self::$last_classes_id = $classes->classes_id;
		});

		// 將導師的班級資料設定為0，刪除排課設定
		static::deleting(function($classes)
		{
			$teacher = Teacher::where('classes_id', '=', $classes->classes_id)->update(array('classes_id' => 0));
			$courseUnit = Courseunit::where('classes_id', '=', $classes->classes_id)->delete();
		});
	}

	public function year()
	{
		return $this->belongsTo('Year', 'year_id');
	}

	public function teacher()
	{
		return $this->belongsTo('Teacher', 'teacher_id');
	}

	public function courseunit()
	{
		return $this->hasMany('Courseunit', 'classes_id');
	}

	// 同步更新教師的班級資料
	public static function syncTeacher()
	{
		$classes = Classes::find(self::$last_classes_id);
		try {
			$teacher = Teacher::where('classes_id', '=', $classes->classes_id)->update(array('classes_id' => 0));
			if ($classes->teacher_id > 0) {
				$teacher = Teacher::find($classes->teacher_id)->update(array('classes_id' => $classes->classes_id));
			}
		} catch (Exception $e) {

		}
	}

	public static function getClassesSelectArray()
	{
		$classesSelectArray[0] = '無';
		$classes = Classes::orderBy('classes_name')->get();

		foreach ($classes as $classItem) {
			$string = $classItem->classes_name;
			if ($teacher = $classItem->teacher) {
				$string .= '（' . $teacher->teacher_name . '）';
			}
			$classesSelectArray[$classItem->classes_id] = $string;
		}

		return $classesSelectArray;
	}

}
?>