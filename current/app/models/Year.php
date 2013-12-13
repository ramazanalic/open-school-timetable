<?php
class Year extends Eloquent
{
	protected $table = 'year';
	protected $primaryKey = 'year_id';
	public $timestamps = false;
	protected $guarded = array('year_id');

	public function classes()
	{
		return $this->hasMany('Classes', 'year_id');
	}

	/**
	 * 刪除年級會連帶刪除底下的年級
	 */
	public function delete()
	{
		Classes::where('year_id', '=', $this->year_id)->delete();
		return parent::delete();
	}

}
?>