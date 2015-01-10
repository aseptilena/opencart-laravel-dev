<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
	protected $table = 'customer_transaction';
	protected $primaryKey = 'customer_transaction_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array('order_id', 'profit_record_id', 'description', 'amount');
}