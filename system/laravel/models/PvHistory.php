<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PvHistory extends Model
{
	protected $fillable = array('profit', 'total', 'rate');

	public function customer()
	{
		return $this->belongsTo('App\Eloquent\Customer');
	}
}