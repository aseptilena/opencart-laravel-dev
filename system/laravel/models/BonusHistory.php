<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class BonusHistory extends Model
{
	const NTREE_BONUS = 1;
    const BTREE_BONUS = 2;
    const LEADER_BONUS = 3;

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
			case self::NTREE_BONUS:
				return '來自於消費分紅';
				break;
			case self::BTREE_BONUS:
				return '來自於行銷分紅';
				break;
			case self::LEADER_BONUS:
				return '來自於領導分紅';
				break;
			default:
				return 'None';
				break;
		}
	}
}