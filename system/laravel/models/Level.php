<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
	public function pass($customer)
	{
		$condition = unserialize($this->condition);
		$ready_levels = explode(',', $customer->ready_levels);
		if (count($ready_levels) > 0) {
			$ready_levels = array_map('intval', $ready_levels);
			if (in_array($this->id, $ready_levels)) {
				$now = new \DateTime('NOW');
				$now->modify('first day of this month');
				$last_month = $now->format('Y-m');
				$consumption = $customer->team_consumption_between($last_month, $last_month);
				if ($consumption >= $condition['next']) {
					return 'pass';
				}
			}
		}

		if (isset($condition['barrier'])) {
			$total = $customer->total_team_consumption();
			if ($total < $condition['barrier']) {
				return 'fail';
			}
		}
		if (isset($condition['downline']) && isset($condition['level'])) {
			$downline = $condition['downline'];
			$level = Level::find($condition['level']);

			$count = 0;
			$descendants = $customer->ntree->descendantsAndSelf()->with('customer')->get();
			foreach ($descendants as $descendant) {
				if ($descendant->customer->customer_id == $customer->customer_id) {
					continue;
				}
				if ($this->getPosition($descendant->customer->level_id) >= $level->position) {
					$count ++;
				}
			}
			if ($count < $downline) {
				return 'fail';
			}
			else {
				if (isset($condition['next'])) {
					return 'next';
				}
			}
		}

		return 'pass';
	}
	public function getPosition($level_id)
	{
		static $all_level = null;
		if (is_null($all_level)) {
			$all_level = Level::all();
		}
		foreach ($all_level as $level) {
			if ($level->id == $level_id) {
				return $level->position;
			}
		}
	}
}