<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class DepositHistory extends Model
{
	public function user()
	{
		return $this->belongsTo('App\Eloquent\User');
	}
}