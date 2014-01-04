<?php
class Classroom extends Eloquent
{
	protected $table = 'classroom';
	protected $primaryKey = 'classroom_id';
	public $timestamps = false;
	protected $guarded = array('classroom_id');

	public static function boot()
	{
		parent::boot();

		// 刪除相關排課設定
		static::deleting(function($classroom)
		{
			$courseUnit = Courseunit::where('classroom_id', '=', $classroom->classroom_id)->delete();
		});
	}

}
?>