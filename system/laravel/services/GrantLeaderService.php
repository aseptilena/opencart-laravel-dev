<?php namespace App\Service;

use Illuminate\Database\Capsule\Manager as DB;
use App\Eloquent\Customer;
use App\Eloquent\BonusHistory;

class GrantLeaderService
{
	protected $customer;

	protected $date;

	protected $grant_money;

	protected $total_consumption;

	public function __construct($customer, $date, $grant_money, $total_consumption)
	{
		$this->customer = $customer;
		$this->date = $date;
		$this->grant_money = $grant_money;
		$this->total_consumption = $total_consumption;
	}
	public function grantLeaderBonus()
	{
		$month = $this->date->format('Y-m');
		$consumption = $this->customer->profit_record_summary(array('start'=>$month, 'end'=>$month));
		$consumption = $consumption->consumption;
		$total_bonus = $this->grant_money * $consumption / $this->total_consumption;

		$this->customer->bonus_histories()->create([
			'source_id' => $this->customer->customer_id,
			'bonus' => $total_bonus,
			'rate' => ($consumption / $this->total_consumption * 100),
			'amount' => $this->grant_money,
			'date' => $this->date,
			'type' => BonusHistory::LEADER_BONUS
		]);

		$record = $this->customer->profit_record_of_date($this->date);
		$record->leader_bonus = $total_bonus;
		$record->bonus_record .= '已發放領導紅利'.$total_bonus.'元。';
		$record->save();
		$this->customer->customerTransactions()->create([
			'order_id' => 0,
			'profit_record_id' => $record->id,
			'description' => '來自'.$this->date->format('Y年m月').'的領導紅利',
			'amount' => $total_bonus
		]);
		$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'finished leader, bonus:'.$total_bonus]);
	}
}