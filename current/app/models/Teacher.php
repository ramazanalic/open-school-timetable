<?php
use Illuminate\Auth\UserInterface;

class Teacher extends Eloquent implements UserInterface
{
	protected $table = 'teacher';
	protected $primaryKey = 'teacher_id';
	public $timestamps = false;
	protected $guarded = array('teacher_id');
	public static $lastTeacherId;
	public static $teacherSelectArrayCache = null;

	public static function boot()
	{
		parent::boot();

		static::saved(function($teacher)
		{
			self::$lastTeacherId = $teacher->teacher_id;
		});

		// 將班級的導師資料設定為0，刪除排課設定
		static::deleting(function($teacher)
		{
			$classes = Classes::where('teacher_id', '=', $teacher->teacher_id)->update(array('teacher_id' => 0));
			$courseUnit = Courseunit::where('teacher_id', '=', $teacher->teacher_id)->delete();
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

	public function courseunit()
	{
		return $this->hasMany('Courseunit', 'teacher_id');
	}

	// 同步更新班級的導師資料
	public static function syncClasses()
	{
		$teacher = Teacher::find(self::$lastTeacherId);
		try {
			$classes = Classes::where('teacher_id', '=', $teacher->teacher_id)->update(array('teacher_id' => 0));
			if ($teacher->classes_id > 0) {
				$classes = Classes::find($teacher->classes_id)->update(array('teacher_id' => $teacher->teacher_id));
			}
		} catch (Exception $e) {

		}
	}

	// 取得教師選單陣列（包含班級）
	public static function getTeacherSelectArray()
	{
		if (self::$teacherSelectArrayCache == null) {
			$teacherSelectArray[0] = '無';
			$teacher = Teacher::orderBy('teacher_name')->with('classes')->get();

			foreach ($teacher as $teacherItem) {
				$string = $teacherItem->teacher_name;
				if ($teacherItem->classes != null) {
					$string .= '（' . $teacherItem->classes->classes_name . '）';
				}
				$teacherSelectArray[$teacherItem->teacher_id] = $string;
			}

			self::$teacherSelectArrayCache = $teacherSelectArray;
			return $teacherSelectArray;
		} else {
			return self::$teacherSelectArrayCache;
		}
	}

	//傳回最後存入的職稱
	public static function getLastTitleId()
	{
		try {
			return Teacher::find(self::$lastTeacherId)->title->title_id;
		} catch (Exception $e) {
			return 'all';
		}
	}

	// 登入使用
	public function getAuthIdentifier()
	{
		return $this->teacher_id;
	}

	public function getAuthPassword()
	{
		return $this->teacher_password_hash;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

}
?>