<?php namespace App\Eloquent;

class Customer extends EncapsulatedEloquentBase
{
	protected $table = 'customer';
	protected $primaryKey = 'customer_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array('firstname','lastname','email','telephone','fax','wishlist','newsletter');

	public function addresses()
	{
		return $this->hasMany('App\Eloquent\Address');
	}
	public function address()
	{
		return $this->belongsTo('App\Eloquent\Address');
	}

	public function addAddress($params)
	{
		$address = Address::create($params);
		$address->save();
		$this->addresses()->save($address);
		$this->address()->associate($address);
		$this->save();
	}
}
