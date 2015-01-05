<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ProfitHistory extends Model
{
	protected $fillable = array('amount');

	public function scopeBetweenTime($query, $from, $to)
	{
		return $query->whereRaw("(created_at BETWEEN '".$from->format('Y-m-d H:i:s')."' AND '".$to->format('Y-m-d H:i:s')."')");
	}
}
