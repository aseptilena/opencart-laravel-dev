<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class UpgradeHistory extends Model
{
	protected $fillable = array('record');
	
	public function date()
	{
		$date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->created_at);
		return $date->format('Y年m月');
	}
}