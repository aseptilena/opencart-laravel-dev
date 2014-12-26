<?php namespace App\Validation;

use App\Validation\AbstractValidator;

class RegisterValidator extends AbstractValidator
{
	protected $rules = array(
		'firstname'    => 'required|between:1,32',
		'lastname'     => 'required|between:1,32',
		'email'        => 'required|email|max:96|unique:customer',
		'email_unique' => 'unique:customer,email',
		);

	protected $messages = array(
		'firstname.required'  => 'error_firstname',
		'firstname.between'   => 'error_firstname',
		'lastname.required'   => 'error_lastname',
		'lastname.between'    => 'error_lastname',
		'email.max'           => 'error_email',
		'email.email'         => 'error_email',
		'email_unique.unique' => 'error_exists',
	);

	protected $responses = array(
		'firstname'    => 'firstname',
		'lastname'     => 'lastname',
		'email'        => 'email',
		'email_unique' => 'warning',
	);
}