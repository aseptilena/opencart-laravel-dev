<?php namespace App\Eloquent;

class Address extends EncapsulatedEloquentBase
{
	protected $table = 'address';
	protected $primaryKey = 'address_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User');
	}
}
