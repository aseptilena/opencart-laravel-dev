<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
	protected $fillable = array('title', 'commission', 'generation', 'jump', 'leader', 'barrier', 'downline', 'level_id', 'next');

	public function pass($customer)
	{
		if (!$this->jump) {
			if ($customer->level->position + 1 != $this->position) {
				return 'fail';
			}
		}
		$ready_levels = explode(',', $customer->ready_levels);
		if (count($ready_levels) > 0) {
			$ready_levels = array_map('intval', $ready_levels);
			if (in_array($this->id, $ready_levels)) {
				$now = new \DateTime('NOW');
				$now->modify('first day of this month');
				$last_month = $now->format('Y-m');
				$consumption = $customer->team_consumption(array('start'=>$last_month, 'end'=>$last_month));
				if ($consumption >= $this->next) {
					return 'pass';
				}
			}
		}

		if ($this->barrier) {
			$total = $customer->team_consumption();
			if ($total < $this->barrier) {
				return 'fail';
			}
		}
		if ($this->downline && $this->level_id) {
			$downline = $this->downline;
			$level = Level::find($this->level_id);

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
				if ($this->next) {
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
		$description = array();
		if ($this->barrier) {
			$description[] = '個人及介紹會員消費滿'.$this->barrier.'元。';
		}
		if ($this->downline && $this->level_id) {
			$level = Level::find($this->level_id);
			$string = '擁有'.$this->downline.'線'.$level->title;

			if ($this->next) {
				$next = $this->next;
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
		if ($this->next) {
			$team_consumption = $customer->team_consumption();
			$next = $this->next;
			if ($team_consumption >= $next) {
				return '本月個人及介紹會員消費金額為'.number_format($team_consumption).'，目標已達成。';
			}
			else {
				return '本月個人及介紹會員消費金額為'.number_format($team_consumption).'，還差'.number_format($next - $team_consumption).'。';
			}
		}
	}
	static public function leaderLevels($is_collection = false)
	{
		$leader_levels = Level::select('id')->where('leader', '=', 1)->get();
		$leader_levels = $leader_levels->map(function($level) { return $level->id; });
		if ($is_collection) {
			return $leader_levels;
		}
		return $leader_levels->toArray();
	}
}