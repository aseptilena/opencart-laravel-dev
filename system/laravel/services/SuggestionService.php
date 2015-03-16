<?php namespace App\Service;

use App\Eloquent\Suggestion;

class SuggestionService
{
	protected $params;

	protected $opencart;

	public function __construct($params, $opencart)
	{
		$this->params = $params;
		$this->opencart = $opencart;
	}
	public function validate()
	{
		$error = array();

		if ((utf8_strlen($this->params['product']) < 2) || (utf8_strlen($this->params['product']) > 96)) {
      		$error['product'] = '商品名稱請在2之128字之內';
    	}

		return $error;
	}
	public function send()
	{
		$suggestion = new Suggestion;
		$suggestion->fill($this->params);
		$suggestion->save();
	}
}