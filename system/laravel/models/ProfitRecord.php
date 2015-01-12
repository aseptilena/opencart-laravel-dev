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
		$result = ProfitRecord::whereRaw("(date BETWEEN '".$start->format('Y-m-d')."' AND '".$end->format('Y-m-d')."')")->get(array(
				DB::raw('SUM(consumption)'),
				DB::raw('SUM(ntree_bonus)'),
				DB::raw('SUM(btree_bonus)'),
				DB::raw('SUM(leader_bonus)')
			));
		
		$result = $result[0];
		$trans = [
			'consumption' => $result['SUM(consumption)'],
			'ntree_bonus' => $result['SUM(ntree_bonus)'],
			'btree_bonus' => $result['SUM(btree_bonus)'],
			'leader_bonus' => $result['SUM(leader_bonus)'],
			'bonus' => ($result['SUM(ntree_bonus)'] + $result['SUM(btree_bonus)']),
		];
		$trans = (object)$trans;
		return $trans;
	}

	static public function leaderConsumption($start, $end, $leader_levels)
	{
		$result = ProfitRecord::whereRaw("(date BETWEEN '".$start->format('Y-m-d')."' AND '".$end->format('Y-m-d')."')")->whereIn('customer_id', function($query) use ($leader_levels) {
			$query->select('customer_id')
			->from('customer')
			->whereIn('level_id', $leader_levels);
		})->get(array(
			DB::raw('SUM(consumption)')
		));
		$result = $result[0];
		return $result['SUM(consumption)'];
	}
}