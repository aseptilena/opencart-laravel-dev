<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class UpgradeHistory extends Model
{
	protected $fillable = array('date', 'record');
	
	public function month()
	{
		$date = \DateTime::createFromFormat('Y-m-d', $this->date);
		return $date->format('Y年m月');
	}
}