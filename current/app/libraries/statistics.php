<?php
class Statistics
{
	public static function sd($sample)
	{
		$mean = array_sum($sample) / count($sample);
		foreach ($sample as $key => $num) {
			$devs[$key] = pow($num - $mean, 2);
		}
		return sqrt(array_sum($devs) / (count($devs) - 1));
	}

}
?>