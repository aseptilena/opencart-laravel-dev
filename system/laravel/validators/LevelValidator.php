<?php namespace App\Validation;

use App\Validation\AbstractValidator;

class LevelValidator extends AbstractValidator
{
	protected $rules = array(
		'title'      => 'required|between:1,50',
		'commission' => 'required|numeric|max:100',
		'generation' => 'required|numeric|max:99999',
		'jump'       => 'required|boolean',
		'barrier'    => 'numeric|max:10000000000',
		'downline'   => 'numeric|max:99',
		'next'       => 'numeric|max:10000000000',
		);

	protected $messages = array(
		'required' => 'The :attribute field is required.',
		'numeric'  => 'The :attribute must be numeric.',
		'between'  => 'The :attribute must be between :min - :max.',
		'max'      => 'The :attribute must be less than :max.',
		'boolean'  => 'The :attribute must be boolean.',
		);
}