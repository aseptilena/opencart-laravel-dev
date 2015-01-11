<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $fillable = array('date', 'type');
}