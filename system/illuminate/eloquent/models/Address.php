<?php namespace App\Eloquent;

class Address extends EncapsulatedEloquentBase
{
	protected $table = 'address';
	protected $primaryKey = 'address_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array('firstname','lastname','company','address_1','address_2','city','postcode','country_id','zone_id','custom_field');

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User');
	}
}
