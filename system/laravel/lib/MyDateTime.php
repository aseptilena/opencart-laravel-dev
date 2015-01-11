<?php

class MyDateTime
{
	static public function init($type)
	{
		if ($type == 'first_day_of_now') {
			$date = new DateTime('NOW');
			return DateTime::createFromFormat('Y-m-d H:i:s', $date->format('Y-m').'-01 00:00:00');
		}
	}
	static public function convertString($string)
	{
		return DateTime::createFromFormat('Y-m-d H:i:s', $string.'-01 00:00:00');
	}
	static public function fromToNowOption($date)
	{
		$dates = self::fromToNow($date);
		$options = array();
		$options[] = (object)[
			'text' => '全部時間',
			'value' => 'all'
		];
		foreach ($dates as $date) {
			$options[] = (object)[
				'text' => $date->format('Y').'年'.$date->format('m').'月',
				'value' => $date->format('Y-m')
			];
		}
		return $options;
	}
	static public function fromToNow($date)
	{
		$to = new DateTime('NOW');
		$to->modify('first day of this month');
		$from = DateTime::createFromFormat('Y-m-d', $date);
		$from->modify('first day of this month');
		$dates = array();
		while ($from <= $to) {
			$dates[] = clone $from;
			$from->modify('+1 month');
		}
		return array_reverse($dates);
	}
}