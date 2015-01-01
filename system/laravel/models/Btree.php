<?php namespace App\Eloquent;

use Baum\Node;

class Btree extends Node
{
	public function customer()
	{
		return $this->belongsTo('App\Eloquent\Customer');
	}
}