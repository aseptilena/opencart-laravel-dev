<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
	protected $table = 'zone';
	protected $primaryKey = 'zone_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

}
