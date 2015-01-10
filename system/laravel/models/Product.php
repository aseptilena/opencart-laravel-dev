<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table = 'product';
	protected $primaryKey = 'product_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array();

	public function cooperation()
	{
		return $this->belongsTo('App\Eloquent\Cooperation');
	}
}