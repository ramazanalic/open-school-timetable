<?php
class Title extends Eloquent
{
	protected $table = 'title';
	protected $primaryKey = 'title_id';
	public $timestamps = false;
	protected $guarded = array('title_id');

	public function teacher()
	{
		return $this->hasMany('Teacher', 'title_id');
	}

	/**
	 * 刪除職稱會讓該職稱教師title_id設定為0
	 */
	public function delete()
	{
		$teachers = Teacher::where('title_id', '=', $this->title_id)->get();
		foreach ($teachers as $teacher) {
			$teacher->title_id = 0;
			$teacher->save();
		}

		parent::delete();
	}

}
?>