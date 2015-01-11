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
	public function deposits()
	{
		return $this->hasMany('App\Eloquent\Deposit');
	}
	public function upgradeHistories()
	{
		return $this->hasMany('App\Eloquent\UpgradeHistory');
	}
	public function customerTransactions()
	{
		return $this->hasMany('App\Eloquent\CustomerTransaction');
	}
	public function grantHistories()
	{
		return $this->hasMany('App\Eloquent\GrantHistory');
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
	public function setPassLevels($levels, $date)
	{
		if (count($levels) == 0) {
			$this->upgradeHistories()->create([
				'date' => $date,
				'record' => '本月無升級'
			]);
			return;
		}
		$level = $levels[0];
		$this->upgradeHistories()->create([
			'date' => $date,
			'record' => '升級到「'.$level->title.'」'
		]);

		$this->level_id = $level->id;
	}
	public function setReadyLevels($levels)
	{
		$collects = array();
		foreach ($levels as $level) {
			$collects[] = $level->id;
		}
		$this->ready_levels = implode(',', $collects);
	}
	public function getReadyLevels()
	{
		if ($this->ready_levels == '') {
			return array();
		}
		$ready_levels = explode(',', $this->ready_levels);
		$collects = array();
		foreach ($ready_levels as $level) {
			$collects[] = Level::find($level);
		}
		return $collects;
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
			return \DateTime::createFromFormat('Y-m-d H:i:s', substr($this->date_added, 0, 7).'-01 00:00:00');
		}
	}

	public function team_consumption($period = false, $flush = false)
	{
		if (is_array($period)) {
			$start = $period['start'];
			$end = $period['end'];
		}
		else {
			$start = $this->joining_month('string_only_month');
			$now = new \DateTime('NOW');
			$now->modify('first day of this month');
			$end = $now->format('Y-m');
		}
		static $cache = array();
		$cache_key = (int)$this->customer_id.'_'.$start.'_'.$end;
		if (isset($cache[$cache_key]) && !$flush)
			return $cache[$cache_key];

		$descendants = $this->ntreeDescendantsAndSelfWithCustomer();
		$sum = 0;
		foreach ($descendants as $descendant) {
			$sum += $descendant->customer->profit_record_summary(array('start'=>$start, 'end'=>$end), $flush)->consumption;
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

		$descendants = $this->btree->descendantsAndSelf()->with('customer')->get();

		$cache[$cache_key] = $descendants;
		return $cache[$cache_key];
	}

	public function profit_record_summary($period = false, $flush = false)
	{
		if (is_array($period)) {
			$start = $period['start'];
			$end = $period['end'];
		}
		else {
			$now = new \DateTime('NOW');
			$now->modify('first day of this month');
			$end = $now->format('Y-m');
			$start = $this->joining_month('string_only_month');
		}
		static $cache = array();
		$cache_key = (int)$this->customer_id.'_'.$start.'_'.$end;
		if (isset($cache[$cache_key]) && !$flush)
			return $cache[$cache_key];

		$start_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $start.'-01 00:00:00');
		$end_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $end.'-01 00:00:00');

		$result = $this->profit_records()->whereRaw("(date BETWEEN '".$start_datetime->format('Y-m-d H:i:s')."' AND '".$end_datetime->format('Y-m-d H:i:s')."')")->get(array(
				DB::raw('SUM(consumption)'),
				DB::raw('SUM(ntree_bonus)'),
				DB::raw('SUM(btree_bonus)')
			));
		$result = $result[0];
		$trans = [
			'consumption' => $result['SUM(consumption)'],
			'ntree_bonus' => $result['SUM(ntree_bonus)'],
			'btree_bonus' => $result['SUM(btree_bonus)'],
			'bonus' => ($result['SUM(ntree_bonus)'] + $result['SUM(btree_bonus)']),
		];
		$trans = (object)$trans;

		$cache[$cache_key] = $trans;
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

	public function calculateNtreeBonus($descendant, $date)
	{
		$tree_id = $this->ntree->id;

		$ancestors = $descendant->ancestorsAndSelf()->with('customer')->get()->reverse();
		$used_bouns = 0;
		foreach ($ancestors as $ancestor) {
			if ($ancestor->id == $tree_id) {
				$gain = $ancestor->customer->level->commission - $used_bouns;
				if ($gain < 0)
					$gain = 0;
				return array($gain, $descendant->customer->profit_record_of_date($date)->consumption);
			}
			if ($ancestor->customer->level->commission > $used_bouns) {
				$used_bouns = $ancestor->customer->level->commission;
			}
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
