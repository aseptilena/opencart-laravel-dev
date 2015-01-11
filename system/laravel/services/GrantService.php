<?php namespace App\Service;

use Illuminate\Database\Capsule\Manager as DB;
use App\Eloquent\Customer;
use App\Eloquent\Btree;
use App\Eloquent\BonusHistory;
use App\Eloquent\GrantHistory;

class GrantService
{
	protected $customer;

	protected $date;

	protected $store;

	public function __construct($customer, $date, $store)
	{
		$this->customer = $customer;
		$this->date = $date;
		$this->store = $store;
	}

	public function grant()
	{
		$this->grantNtreeBonus();
		$this->grantBtreeBonus();
	}

	public function grantNtreeBonus()
	{
		$tree = $this->customer->ntree;
		if (!$tree) {
			$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'do not have ntree']);
			return;
		}

		$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'start grant ntree']);

		$descendants = $tree->descendantsAndSelf()->with('customer')->get();
		$total_bonus = 0;

		$collect_history = array();
		foreach ($descendants as $descendant) {
			list($gain, $consumption) = $this->customer->calculateNtreeBonus($descendant, $this->date);
			$bonus = $gain / 100 * $consumption;
			$info = [
				'source_id' => $descendant->customer->customer_id,
				'bonus' => $bonus,
				'rate' => $gain,
				'amount' => $consumption,
				'date' => $this->date,
				'type' => BonusHistory::NTREE_CONSUMPTION
			];
			if ($this->store) {
				$this->customer->bonus_histories()->create($info);
			}
			$collect_history[] = $info;
			$total_bonus += $bonus;
		}
		if ($this->store) {
			$record = $this->customer->profit_record_of_date($this->date);
			$record->ntree_bonus = $total_bonus;
			$record->bonus_record .= '已發放消費紅利'.$total_bonus.'元。';
			$record->save();
			$this->customer->customerTransactions()->create([
				'order_id' => 0,
				'profit_record_id' => $record->id,
				'description' => '來自'.$this->date->format('Y年m月').'的消費紅利',
				'amount' => $total_bonus
			]);
			$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'finished ntree, bonus:'.$total_bonus]);
		}
		return [
			'total_bonus' => $total_bonus,
			'histories' => $collect_history,
		];
	}

	public function grantBtreeBonus()
	{
		$tree = $this->customer->btree;
		if (!$tree) {
			$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'do not have btree']);
			return;
		}
		$barreir = 1000;
		if ($this->customer->accumulated_consumption() < $barreir) {
			$record = $this->customer->profit_record_of_date($this->date);
			$record->bonus_record .= '金額未超過'.$barreir.'元，無行銷紅利。';
			$record->save();
			$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'accumulated consumption less than 1000']);
			return;
		}
		$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'start grant btree']);

		if ($this->store) {
			$service = new BtreeService($this->customer);
			$service->bulidBtree();
			$service->doBtree();
		}

		$generation = $this->customer->level->generation;

		$tree = $this->store ? $this->customer->calculateTree : $this->customer->btree;
		$descendants = $tree->descendantsAndSelf()->limitDepth($generation)->with('customer')->get();
		$total_bonus = 0;

		$collect_history = array();
		foreach ($descendants as $descendant) {
			$consumption = $descendant->customer->accumulated_consumption();
			$gain = 2;
			$bonus = $gain / 100 * $consumption;
			$info = [
				'source_id' => $descendant->customer->customer_id,
				'bonus' => $bonus,
				'rate' => $gain,
				'amount' => $consumption,
				'date' => $this->date,
				'type' => BonusHistory::BTREE_CONSUMPTION
			];
			if ($this->store) {
				$this->customer->bonus_histories()->create($info);
			}
			$collect_history[] = $info;
			$total_bonus += $bonus;
		}
		if ($this->store) {
			$record = $this->customer->profit_record_of_date($this->date);
			$record->btree_bonus = $total_bonus;
			$record->bonus_record .= '已發放行銷紅利'.$total_bonus.'元。';
			$record->save();
			$this->customer->customerTransactions()->create([
				'order_id' => 0,
				'profit_record_id' => $record->id,
				'description' => '來自'.$this->date->format('Y年m月').'的行銷紅利',
				'amount' => $total_bonus
			]);
			$this->customer->grantHistories()->create(['date' => $this->date, 'record' => 'finished btree, bonus:'.$total_bonus]);
		}
		return [
			'total_bonus' => $total_bonus,
			'histories' => $collect_history,
		];
	}
}