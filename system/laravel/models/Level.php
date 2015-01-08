<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
	public function getCondition()
	{
		return unserialize($this->condition);
	}
	public function pass($customer)
	{
		$condition = $this->getCondition();
		$ready_levels = explode(',', $customer->ready_levels);
		if (count($ready_levels) > 0) {
			$ready_levels = array_map('intval', $ready_levels);
			if (in_array($this->id, $ready_levels)) {
				$now = new \DateTime('NOW');
				$now->modify('first day of this month');
				$last_month = $now->format('Y-m');
				$consumption = $customer->team_consumption(array('start'=>$last_month, 'end'=>$last_month));
				if ($consumption >= $condition['next']) {
					return 'pass';
				}
			}
		}

		if (isset($condition['barrier'])) {
			$total = $customer->team_consumption();
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
	public function conditionDescription()
	{
		$condition = $this->getCondition();
		$description = array();
		if (isset($condition['barrier'])) {
			$description[] = '個人及介紹會員消費滿'.$condition['barrier'].'元。';
		}
		if (isset($condition['downline']) && isset($condition['level'])) {
			$level = Level::find($condition['level']);
			$string = '擁有'.$condition['downline'].'線'.$level->title;

			if (isset($condition['next'])) {
				$next = $condition['next'];
				$next /= 10000;
				$string .= '，且隔月業績'.$next.'萬PV';
			}
			$string .= '。';
			$description[] = $string;
		}
		if (count($description) == 1) {
			return $description[0];
		}
		else {
			return implode('<br>', $description);
		}
	}
	public function achieveDescription($customer)
	{
		$condition = $this->getCondition();
		if (isset($condition['next'])) {
			$team_consumption = $customer->team_consumption();
			$next = $condition['next'];
			if ($team_consumption >= $next) {
				return '本月個人及介紹會員消費金額為'.number_format($team_consumption).'，目標已達成。';
			}
			else {
				return '本月個人及介紹會員消費金額為'.number_format($team_consumption).'，還差'.number_format($next - $team_consumption).'。';
			}
		}
	}
}