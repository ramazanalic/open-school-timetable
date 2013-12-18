<?php
class Teacher extends Eloquent
{
	protected $table = 'teacher';
	protected $primaryKey = 'teacher_id';
	public $timestamps = false;
	protected $guarded = array('teacher_id');
	public static $last_teacher_id;

	public static function boot()
	{
		parent::boot();

		static::saved(function($teacher)
		{
			self::$last_teacher_id = $teacher->teacher_id;
		});

		// 將班級的導師資料設定為0
		static::deleting(function($teacher)
		{
			$classes = Classes::where('teacher_id', '=', $teacher->teacher_id)->update(array('teacher_id' => 0));
		});
	}

	public function title()
	{
		return $this->belongsTo('Title', 'title_id');
	}

	public function classes()
	{
		return $this->belongsTo('Classes', 'classes_id');
	}

	// 同步更新班級的導師資料
	public static function syncClasses()
	{
		$teacher = Teacher::find(self::$last_teacher_id);
		try {
			$classes = Classes::where('teacher_id', '=', $teacher->teacher_id)->update(array('teacher_id' => 0));
			if ($teacher->classes_id > 0) {
				$classes = Classes::find($teacher->classes_id)->update(array('teacher_id' => $teacher->teacher_id));
			}
		} catch (Exception $e) {

		}
	}

}
?>