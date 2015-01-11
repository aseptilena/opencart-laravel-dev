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
}