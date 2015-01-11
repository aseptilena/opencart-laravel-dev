<?php namespace App\Service;

use App\Eloquent\Customer;
use App\View\ViewManager;

class TreeHistoryService
{
	protected $customer;

	protected $type;

	public function __construct($customer, $type)
	{
		$this->customer = $customer;
		$this->type = $type;
	}
	public function getContent()
	{
		$start = $this->customer->joining_month('datetime');
		$end = new \DateTime('NOW');
		$end->modify('first day of this month');

		$collects = array();
		if ($this->type == 'ntree') {
			if (!$this->customer->ntree) {
				return 'No Ntree History';
			}
			$descendants = $this->customer->ntreeDescendantsAndSelfWithCustomer();
		}
		else if ($this->type == 'btree') {
			if (!$this->customer->btree) {
				return 'No Btree History';
			}
			$descendants = $this->customer->btreeDescendantsAndSelfWithCustomer();
		}
		$total = count($descendants);
		while ($start <= $end) {
			$next_month = clone $start;
			$next_month->modify('+1 month');
			$count = 0;
			foreach ($descendants as $key => $descendant) {
				if ($descendant->customer->customer_id == $this->customer->customer_id) {
					unset($descendants[$key]);
					continue;
				}
				$date_added = \DateTime::createFromFormat('Y-m-d H:i:s', $descendant->customer->date_added);
				if ($start < $date_added && $date_added < $next_month) {
					$count++;
					unset($descendants[$key]);
				}
			}
			$collects[] = (object)['date' => (clone $start), 'count' => $count];
			$start->modify('+1 month');
		}
		$data['tree_histories'] = $collects;
		$data['total'] = $total;

		$r = ViewManager::loadBlade('not-sure', 'customer/tree_history.blade.php', $data);
		return $r->render();
	}
}