<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Customer extends Model
{
	protected $table = 'customer';
	protected $primaryKey = 'customer_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array('firstname','lastname','email','telephone','fax','wishlist','newsletter');

	public function addresses()
	{
		return $this->hasMany('App\Eloquent\Address');
	}
	public function address()
	{
		return $this->belongsTo('App\Eloquent\Address');
	}
	public function ntree()
	{
		return $this->hasOne('App\Eloquent\Ntree');
	}
	public function btree()
	{
		return $this->hasOne('App\Eloquent\Btree');
	}
	public function calculate_tree()
	{
		return $this->hasOne('App\Eloquent\CalculateTree');
	}

	public function addBtreeChild($customer)
	{
		$descendants = $this->btree->descendantsAndSelf()->with('customer')->get()->toHierarchy();
		$find_descendant = $this->findBtreePosition($descendants);

		$child = Btree::create(['name' => 'Child']);
		$child->customer()->associate($customer);
		$child->save();
		$child->makeChildOf($find_descendant);
	}

	public function findBtreePosition($descendants) {
		foreach ($descendants as $descendant) {
			if ($descendant->children->count() == 0) {
				return $descendant;
			}
		}
		foreach ($descendants as $descendant) {
			if ($descendant->children->count() == 1) {
				return $descendant;
			}
		}
		$collects = array();
		foreach ($descendants as $descendant) {
			foreach ($descendant->children as $child) {
				$collects[] = $child;
			}
		}
		return $this->findBtreePosition($collects);
	}

	public function pv_histories()
	{
		return $this->hasMany('App\Eloquent\PvHistory');
	}

	public function profit_records()
	{
		return $this->hasMany('App\Eloquent\ProfitRecord');
	}

	public function profit_histories()
	{
		return $this->hasMany('App\Eloquent\ProfitHistory');
	}
	public function bonus_histories()
	{
		return $this->hasMany('App\Eloquent\BonusHistory');
	}

	public function current_profit_record()
	{
		$now_month = new \DateTime('NOW');
		$now_month->modify('first day of this month');
		return $this->profit_record_of_date($now_month);
	}

	public function profit_record_of_date($date)
	{
		$record = $this->profit_records()->date($date)->get();
		if (!$record->isEmpty())
			return $record[0];
		return $this->profit_records()->create(array('date' => $date->format('Y-m-01')));
	}

	public function consume($amount)
	{
		$this->profit_histories()->create(array(
			'amount' => $amount,
			));
		$this->current_profit_record()->increment('consumption', $amount);
	}

	public function grantNtreeBonus($date, $store = false)
	{
		$descendants = $this->ntree->descendantsAndSelf()->with('customer')->get();
		$total_bonus = 0;
		foreach ($descendants as $descendant) {
			list($gain, $consumption) = $this->calculateNtreeBonus($descendant, $date);
			$bonus = $gain / 100 * $consumption;
			if ($store) {
				$this->bonus_histories()->create(array(
					'source_id' => $descendant->customer->customer_id,
					'bonus' => $bonus,
					'rate' => $gain,
					'amount' => $consumption,
					'date' => $date,
					'type' => BonusHistory::NTREE_CONSUMPTION
				));
			}
			$total_bonus += $bonus;
		}
		if ($store) {
			$record = $this->profit_record_of_date($date);
			$record->bonus = $total_bonus;
			$record->save();
		}
		return $total_bonus;
	}

	public function calculateNtreeBonus($descendant, $date)
	{
		$tree_id = $this->ntree->id;

		$ancestors = $descendant->ancestorsAndSelf()->with('customer')->get()->reverse();
		$used_bouns = 0;
		foreach ($ancestors as $ancestor) {
			if ($ancestor->id == $tree_id) {
				$gain = $ancestor->customer->bonus_rate - $used_bouns;
				return array($gain, $descendant->customer->profit_record_of_date($date)->consumption);
			}
			if ($ancestor->customer->bonus_rate > $used_bouns) {
				$used_bouns = $ancestor->customer->bonus_rate;
			}
		}
	}


	public function grantBtreeBonus($date, $store = false)
	{
		$descendants = $this->btree->descendantsAndSelf()->with('customer')->get();
		$total_bonus = 0;
		foreach ($descendants as $descendant) {
			list($gain, $consumption) = $this->calculateNtreeBonus($descendant, $date);
			$bonus = $gain / 100 * $consumption;
			if ($store) {
				$this->bonus_histories()->create(array(
					'source_id' => $descendant->customer->customer_id,
					'bonus' => $bonus,
					'rate' => $gain,
					'amount' => $consumption,
					'date' => $date,
					'type' => BonusHistory::NTREE_CONSUMPTION
				));
			}
			$total_bonus += $bonus;
		}
		if ($store) {
			$record = $this->profit_record_of_date($date);
			$record->bonus = $total_bonus;
			$record->save();
		}
		return $total_bonus;
	}

	public function calculateBtreeBonus($descendant, $date)
	{
		$tree_id = $this->ntree->id;

		$ancestors = $descendant->ancestorsAndSelf()->with('customer')->get()->reverse();
		$used_bouns = 0;
		foreach ($ancestors as $ancestor) {
			if ($ancestor->id == $tree_id) {
				$gain = $ancestor->customer->bonus_rate - $used_bouns;
				return array($gain, $descendant->customer->profit_record_of_date($date)->consumption);
			}
			if ($ancestor->customer->bonus_rate > $used_bouns) {
				$used_bouns = $ancestor->customer->bonus_rate;
			}
		}
	}

	public function passNtreeBonus($money)
	{
		$ancestors = $this->ntree->ancestorsAndSelf()->with('customer')->get()->reverse();
		$used_bouns = 0;
		foreach ($ancestors as $ancestor) {
			if ($ancestor->customer->bonus_rate > $used_bouns) {
				$gain = $ancestor->customer->bonus_rate - $used_bouns;
				$used_bouns = $ancestor->customer->bonus_rate;
				$ancestor->customer->addProfit($money, $gain);
			}
		}
	}

	public function addProfit($total, $rate)
	{
		$profit = $total * $rate / 100.0;
		$this->pv_histories()->create(array(
			'profit' => $profit,
			'total' => $total,
			'rate' => $rate,
			));
		$this->increment('pv', $profit);

		$ancestors = $this->ntree->ancestorsAndSelf()->with('customer')->get();
		foreach ($ancestors as $ancestor) {
			$ancestor->customer->increment('total_pv', $profit);
		}
	}

	public function passBtreeBonus($money)
	{
		$ancestors = $this->btree->ancestorsAndSelf()->with('customer')->get()->reverse();
		$generation_count = 5;
		foreach ($ancestors as $ancestor) {
			$ancestor->customer->addProfit($money, 2);

			$generation_count--;
			if ($generation_count == 0)
				break;
		}
	}

	public function setPassword($password)
	{
		$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$this->status = 1;
		$this->salt = $salt;
		$this->password = sha1($salt.sha1($salt.sha1($password)));
	}

	public function addAddress($params, $default = false)
	{
		$address = Address::create($params);
		$this->addresses()->save($address);
		if ($default) {
			$this->address()->associate($address);
			$this->save();
		}
	}
	public function getAddresses()
	{
		$addresses_data = array();
		foreach ($this->addresses as $address) {
			$addresses_data[$address->address_id] = $address->getData();
		}
		return $addresses_data;
	}
}
