<?php namespace App\Validation;

use App\Validation\ValidableInterface;

class RegisterValidator extends AbstractValidator
{

	protected $rules = array(
		'firstname' => 'required|between:1,32',
		'lastname' => 'required|between:1,32',
		'email' => 'required|email|max:96|unique:customer',
		);
  
}