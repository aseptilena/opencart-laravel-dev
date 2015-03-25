<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Vendor extends Model
{
	protected $table = 'vendors';
	protected $primaryKey = 'vendor_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';
}