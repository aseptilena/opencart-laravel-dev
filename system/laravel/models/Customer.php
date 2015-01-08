<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use App\Service\BtreeService;

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
	public function calculateTree()
	{
		return $this->hasOne('App\Eloquent\CalculateTree');
	}
	public function level()
	{
		return $this->belongsTo('App\Eloquent\Level');
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
	public function setPassLevels($levels)
	{
		if (count($levels) == 0) {
			return;
		}
		$level = $levels[0];
		$this->level_id = $level->id;
		if (!$this->lock_condition) {
			$this->commission = $level->commission;
			$this->generation = $level->generation;
		}
	}
	public function setReadyLevels($levels)
	{
		$collects = array();
		foreach ($levels as $level) {
			$collects[] = $level->id;
		}
		$this->ready_levels = implode(',', $collects);
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

	public function joining_month($format)
	{
		if ($format == 'string_only_month') {
			return substr($this->date_added, 0, 7);
		}
		else if ($format == 'string_all_time') {
			return substr($this->date_added, 0, 7).'-01 00:00:00';
		}
		else if ($format == 'datetime') {
			return DateTime::createFromFormat('Y-m-d H:i:s', substr($this->date_added, 0, 7).'-01 00:00:00');
		}
	}
	public function total_team_consumption()
	{
		$now = new \DateTime('NOW');
		$now->modify('first day of this month');
		$date_to = $now->format('Y-m');
		$date_from = $this->joining_month('string_only_month');
		return $this->team_consumption_between($date_from, $date_to);
	}
	public function team_consumption_between($date_from, $date_to, $flush = false)
	{
		static $cache = array();
		$cache_key = (int)$this->customer_id.'_'.$date_from.'_'.$date_to;
		if (isset($cache[$cache_key]) && !$flush)
			return $cache[$cache_key];
		echo "team_consumption_between: $cache_key <br>";

		$descendants = $this->ntree->descendantsAndSelf()->with('customer')->get();
		$sum = 0;
		foreach ($descendants as $descendant) {
			$sum += $descendant->customer->profit_record_summary($date_from, $date_to)['SUM(consumption)'];
		}

		$cache[$cache_key] = $sum;
		return $cache[$cache_key];
	}
	public function ntreeDescendantsAndSelfWithCustomer($flush = false)
	{
		static $cache = array();
		$cache_key = (int)$this->customer_id;
		if (isset($cache[$cache_key]) && !$flush)
			return $cache[$cache_key];
		echo "ntreeDescendantsAndSelfWithCustomer: $cache_key <br>";

		$descendants = $this->ntree->descendantsAndSelf()->with('customer')->get();

		$cache[$cache_key] = $descendants;
		return $cache[$cache_key];
	}
	public function btreeDescendantsAndSelfWithCustomer($flush = false)
	{
		static $cache = array();
		$cache_key = (int)$this->customer_id;
		if (isset($cache[$cache_key]) && !$flush)
			return $cache[$cache_key];
		// echo "btreeDescendantsAndSelfWithCustomer: $cache_key <br>";

		$descendants = $this->btree->descendantsAndSelf()->with('customer')->get();

		$cache[$cache_key] = $descendants;
		return $cache[$cache_key];
	}

	public function profit_record_summary($from, $to, $flush = false)
	{
		static $cache = array();
		$cache_key = (int)$this->customer_id.'_'.$from.'_'.$to;
		if (isset($cache[$cache_key]) && !$flush)
			return $cache[$cache_key];
		// echo "profit_record_summary: $cache_key <br>";

		$from = \DateTime::createFromFormat('Y-m-d H:i:s', $from.'-01 00:00:00');
		$to = \DateTime::createFromFormat('Y-m-d H:i:s', $to.'-01 00:00:00');

		$result = $this->profit_records()->whereRaw("(date BETWEEN '".$from->format('Y-m-d H:i:s')."' AND '".$to->format('Y-m-d H:i:s')."')")->get(array(
				DB::raw('SUM(consumption)'),
				DB::raw('SUM(ntree_bonus)'),
				DB::raw('SUM(btree_bonus)')
			));

		$cache[$cache_key] = $result[0];
		return $cache[$cache_key];
	}

	public function bonus_histories_between($from, $to)
	{
		$from = \DateTime::createFromFormat('Y-m-d H:i:s', $from.'-01 00:00:00');
		$to = \DateTime::createFromFormat('Y-m-d H:i:s', $to.'-01 00:00:00');

		return $this->bonus_histories()->whereRaw("(date BETWEEN '".$from->format('Y-m-d H:i:s')."' AND '".$to->format('Y-m-d H:i:s')."')")->get();
	}

	public function profit_histories_between($from, $to)
	{
		return $this->profit_histories()->betweenTime($from, $to)->get();
	}

	public function accumulated_consumption()
	{
		return $this->profit_records()->where('used_btree', '=', 0)->sum('consumption');
	}

	public function own_months()
	{
		$to = new \DateTime('NOW');
		$to->modify('first day of this month');
		$from = \DateTime::createFromFormat('Y-m-d', substr($this->date_added, 0, 10));
		$from->modify('first day of this month');
		$dates = array();
		while ($from <= $to) {
			$dates[] = clone $from;
			$from->modify('+1 month');
		}
		return array_reverse($dates);
	}

	public function own_months_options()
	{
		$dates = $this->own_months();
		$options = array();
		$options[] = [
			'text' => '全部時間',
			'value' => 'all'
		];
		foreach ($dates as $date) {
			$options[] = [
				'text' => $date->format('Y').'年'.$date->format('m').'月',
				'value' => $date->format('Y-m')
			];
		}
		return $options;
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

		$collect_history = array();
		foreach ($descendants as $descendant) {
			list($gain, $consumption) = $this->calculateNtreeBonus($descendant, $date);
			$bonus = $gain / 100 * $consumption;
			$info = [
				'source_id' => $descendant->customer->customer_id,
				'bonus' => $bonus,
				'rate' => $gain,
				'amount' => $consumption,
				'date' => $date,
				'type' => BonusHistory::NTREE_CONSUMPTION
			];
			if ($store) {
				$this->bonus_histories()->create($info);
			}
			$collect_history[] = $info;
			$total_bonus += $bonus;
		}
		if ($store) {
			$record = $this->profit_record_of_date($date);
			$record->ntree_bonus = $total_bonus;
			$record->save();
		}
		return [
			'total_bonus' => $total_bonus,
			'histories' => $collect_history,
		];
	}

	public function calculateNtreeBonus($descendant, $date)
	{
		$tree_id = $this->ntree->id;

		$ancestors = $descendant->ancestorsAndSelf()->with('customer')->get()->reverse();
		$used_bouns = 0;
		foreach ($ancestors as $ancestor) {
			if ($ancestor->id == $tree_id) {
				$gain = $ancestor->customer->commission - $used_bouns;
				return array($gain, $descendant->customer->profit_record_of_date($date)->consumption);
			}
			if ($ancestor->customer->commission > $used_bouns) {
				$used_bouns = $ancestor->customer->commission;
			}
		}
	}


	public function grantBtreeBonus($date, $store = false)
	{
		if ($this->accumulated_consumption() < 1000) {
			return;
		}

		if ($store) {
			$service = new BtreeService($this);
			$service->bulidBtree();
			$service->doBtree();
		}

		$this->btree_depth = 3;

		$tree = $store ? $this->calculateTree : $this->btree;
		$descendants = $tree->descendantsAndSelf()->limitDepth(2)->with('customer')->get();
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
				'date' => $date,
				'type' => BonusHistory::BTREE_CONSUMPTION
			];
			if ($store) {
				$this->bonus_histories()->create($info);
			}
			$collect_history[] = $info;
			$total_bonus += $bonus;
		}
		if ($store) {
			$record = $this->profit_record_of_date($date);
			$record->btree_bonus = $total_bonus;
			$record->save();
		}
		return [
			'total_bonus' => $total_bonus,
			'histories' => $collect_history,
		];
	}

	// public function passNtreeBonus($money)
	// {
	// 	$ancestors = $this->ntree->ancestorsAndSelf()->with('customer')->get()->reverse();
	// 	$used_bouns = 0;
	// 	foreach ($ancestors as $ancestor) {
	// 		if ($ancestor->customer->commission > $used_bouns) {
	// 			$gain = $ancestor->customer->commission - $used_bouns;
	// 			$used_bouns = $ancestor->customer->commission;
	// 			$ancestor->customer->addProfit($money, $gain);
	// 		}
	// 	}
	// }

	// public function addProfit($total, $rate)
	// {
	// 	$profit = $total * $rate / 100.0;
	// 	$this->pv_histories()->create(array(
	// 		'profit' => $profit,
	// 		'total' => $total,
	// 		'rate' => $rate,
	// 		));
	// 	$this->increment('pv', $profit);

	// 	$ancestors = $this->ntree->ancestorsAndSelf()->with('customer')->get();
	// 	foreach ($ancestors as $ancestor) {
	// 		$ancestor->customer->increment('total_pv', $profit);
	// 	}
	// }

	// public function passBtreeBonus($money)
	// {
	// 	$ancestors = $this->btree->ancestorsAndSelf()->with('customer')->get()->reverse();
	// 	$generation_count = 5;
	// 	foreach ($ancestors as $ancestor) {
	// 		$ancestor->customer->addProfit($money, 2);

	// 		$generation_count--;
	// 		if ($generation_count == 0)
	// 			break;
	// 	}
	// }

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
