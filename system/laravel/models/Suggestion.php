<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
	protected $fillable = array('name', 'email', 'phone', 'comment', 'product', 'url');
}