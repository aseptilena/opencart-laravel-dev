<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
	const STATUS_REQUEST = 1;
	const STATUS_DENIED = 2;
	const STATUS_ACCEPT = 3;

	public function deposit_histories()
	{
		return $this->hasMany('App\Eloquent\DepositHistory');
	}

	static public function all_status()
	{
		return array(
			(object)[
				'id' => self::STATUS_REQUEST,
				'name' => '要求中',
			],
			(object)[
				'id' => self::STATUS_DENIED,
				'name' => '已拒絕',
			],
			(object)[
				'id' => self::STATUS_ACCEPT,
				'name' => '已完成',
			],
		);
	}

	public function statusName()
	{
		switch ($this->status) {
			case self::STATUS_REQUEST:
				return '要求中';
				break;
			case self::STATUS_DENIED:
				return '已拒絕';
				break;
			case self::STATUS_ACCEPT:
				return '已完成';
				break;
			default:
				return '';
				break;
		}
	}
	public function remitName()
	{
		return $this->is_remit == 0 ? '未撥款' : '已撥款';
	}

}