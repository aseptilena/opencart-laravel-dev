<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
	protected $table = 'customer_group';
	protected $primaryKey = 'customer_group_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	public function customers()
	{
		return $this->hasMany('App\Eloquent\Customer');
	}

	public function scopeCooperation($query)
	{
		return $query->where('customer_group_id', '!=', 1);
	}

	public function translation()
	{
		return $this->belongsToMany('App\Eloquent\Language', 'customer_group_description')->withPivot('name', 'description');
	} 
	public function setLanguage($language_id)
	{
		$translation = array('name', 'description');
		foreach ($translation as $value) {
			$this->{$value} = $this->translation->find($language_id)->pivot->{$value};
		}
	} 
}