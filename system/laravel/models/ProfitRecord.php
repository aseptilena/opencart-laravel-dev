<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

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
}