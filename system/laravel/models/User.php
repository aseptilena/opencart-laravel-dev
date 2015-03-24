<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'user';
	protected $primaryKey = 'user_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	public function cooperation()
	{
		return $this->belongsTo('App\Eloquent\Cooperation');
	}
	public function vendor()
	{
		return $this->hasOne('App\Eloquent\Vendor');
	}
}