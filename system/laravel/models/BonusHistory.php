<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class BonusHistory extends Model
{
	const NTREE_CONSUMPTION = 1;
    const BTREE_CONSUMPTION = 2;

	protected $fillable = array('source_id', 'bonus', 'rate', 'amount', 'date', 'type');
}