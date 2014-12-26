<?php namespace App\Validation;

use Illuminate\Validation\Factory;
use Symfony\Component\Translation\Translator;
use App\Validation\ValidableInterface;

abstract class AbstractValidator {

	protected $validator;

	protected $data = array();

	protected $rules = array();

	protected $errorMessages = array();

	protected $errors = array();

	public function with(array $data, array $errorMessages)
	{
		$this->data = $data;
		$this->errorMessages = $errorMessages;
		return $this;
	}

	public function errors()
	{
		return $this->errors;
	}

	public function passes()
	{
		$translator = new Translator('en');
		$factory = new Factory($translator);
		$validator = $factory->make($this->data, $this->rules, $this->errorMessages);

		if($validator->fails())
		{
			$this->errors = $validator->messages();
			return false;
		}

		return true;
	}
}