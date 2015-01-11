<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class ProfitRecord extends Model
{
	protected $fillable = array('date', 'consumption', 'ntree_bonus', 'btree_bonus', 'total');

	public function scopeCurrent($query)
	{
		return $query->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', array(date('m'), date('Y')));
	}

	public function scopeDate($query, $date)
	{
		return $query->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', array($date->format('m'), $date->format('Y')));
	}

	public function month()
	{
		$date = \DateTime::createFromFormat('Y-m-d', $this->date);
		return $date->format('Y年m月');
	}

	static public function summary($start, $end)
	{
		$result = ProfitRecord::whereRaw("(date BETWEEN '".$start->format('Y-m-01 00:00:00')."' AND '".$end->format('Y-m-01 00:00:00')."')")->get(array(
				DB::raw('SUM(consumption)'),
				DB::raw('SUM(ntree_bonus)'),
				DB::raw('SUM(btree_bonus)')
			));
		
		$result = $result[0];
		$trans = [
			'consumption' => $result['SUM(consumption)'],
			'ntree_bonus' => $result['SUM(ntree_bonus)'],
			'btree_bonus' => $result['SUM(btree_bonus)'],
			'bonus' => ($result['SUM(ntree_bonus)'] + $result['SUM(btree_bonus)']),
		];
		$trans = (object)$trans;
		return $trans;
	}
}