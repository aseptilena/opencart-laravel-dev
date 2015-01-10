<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class DepositHistory extends Model
{
	protected $fillable = array('user_id', 'detail', 'comment', 'amount');

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User');
	}

}