<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table = 'country';
	protected $primaryKey = 'country_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

}
