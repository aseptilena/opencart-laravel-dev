<?php namespace App\Eloquent;

class Customer extends EncapsulatedEloquentBase
{
	protected $table = 'customer';
	protected $primaryKey = 'customer_id';
	const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

	public function addresses()
	{
		return $this->hasMany('App\Eloquent\Address');
	}
	public function default_address()
	{
		return $this->belongsTo('App\Eloquent\Address', 'customer_id', 'address_id');
	}
}
