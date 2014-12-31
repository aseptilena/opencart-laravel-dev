<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
	protected $table = 'customer_group';
	protected $primaryKey = 'customer_group_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';
}