<?php namespace App\Eloquent;

use Baum\Node;

class CalculateTree extends Node
{
	protected $fillable = array('parent_id', 'customer_id');

	public function customer()
	{
		return $this->belongsTo('App\Eloquent\Customer');
	}
}