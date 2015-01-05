<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class BonusHistory extends Model
{
	const NTREE_CONSUMPTION = 1;
    const BTREE_CONSUMPTION = 2;

	protected $fillable = array('source_id', 'bonus', 'rate', 'amount', 'date', 'type');

	public function source()
	{
		return $this->belongsTo('App\Eloquent\Customer');
	}
	public function scopeDate($query, $date)
	{
		return $query->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', array($date->format('m'), $date->format('Y')));
	}
	public function typeName()
	{
		switch ($this->type) {
			case self::NTREE_CONSUMPTION:
				return '來自於N Tree分紅';
				break;
			case self::BTREE_CONSUMPTION:
				return '來自於B Tree分紅';
				break;
			default:
				return 'None';
				break;
		}
	}
}