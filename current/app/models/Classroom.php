<?php
class Classroom extends Eloquent
{
	protected $table = 'classroom';
	protected $primaryKey = 'classroom_id';
	public $timestamps = false;
	protected $guarded = array('classroom_id');

	public function courseunit()
	{
		return $this->hasMany('Courseunit', 'classroom_id');
	}

	public static function boot()
	{
		parent::boot();

		// 刪除相關排課設定
		static::deleting(function($classroom)
		{
			$courseUnit = Courseunit::where('classroom_id', '=', $classroom->classroom_id)->delete();
		});

		// 如果沒設定可用時間，預設全部可以使用
		static::saving(function($data)
		{
			if ($data->course_time == 0) {
				$data->course_time = str_repeat('1', 35);
			}
		});
	}

}
?>