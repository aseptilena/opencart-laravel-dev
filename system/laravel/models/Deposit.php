<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
	const STATUS_REQUEST = 1;
	const STATUS_DENIED = 2;
	const STATUS_ACCEPT = 3;

	public function depositHistories()
	{
		return $this->hasMany('App\Eloquent\DepositHistory');
	}

	static public function all_status()
	{
		$array = array(
			self::STATUS_REQUEST,
			self::STATUS_DENIED,
			self::STATUS_ACCEPT,
			);
		$collects = array();
		foreach ($array as $value) {
			$collects[] = (object)[
				'id' => $value,
				'name' => Deposit::statusNameOf($value),
			];
		}
		return $collects;
	}

	public function statusName()
	{
		return Deposit::statusNameOf($this->status);
	}

	static public function statusNameOf($status)
	{
		switch ($status) {
			case self::STATUS_REQUEST:
				return '要求中';
				break;
			case self::STATUS_DENIED:
				return '已拒絕';
				break;
			case self::STATUS_ACCEPT:
				return '已撥款';
				break;
			default:
				return '';
				break;
		}
	}
}