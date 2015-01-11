<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class GrantHistory extends Model
{
	protected $fillable = array('date', 'record');
}