<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CustomerActivity extends Model
{
	protected $table = 'customer_activity';
	protected $primaryKey = 'activity_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array();
}