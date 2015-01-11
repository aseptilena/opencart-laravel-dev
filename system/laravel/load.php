<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('START_DATE', '2014-11-01');

require_once(DIR_SYSTEM.'laravel/vendor/autoload.php');

// foreach (glob(DIR_SYSTEM.'laravel/core/'.'*.php') as $filename) require_once $filename;

require_once(DIR_SYSTEM.'laravel/core/Encapsulator.php');
require_once(DIR_SYSTEM.'laravel/core/EncapsulatedEloquentBase.php');
require_once(DIR_SYSTEM.'laravel/core/ValidatorManager.php');
require_once(DIR_SYSTEM.'laravel/core/AbstractValidator.php');
require_once(DIR_SYSTEM.'laravel/core/ViewManager.php');


foreach (glob(DIR_SYSTEM.'laravel/lib/'.'*.php') as $filename) require_once $filename;
foreach (glob(DIR_SYSTEM.'laravel/models/'.'*.php') as $filename) require_once $filename;
foreach (glob(DIR_SYSTEM.'laravel/validators/'.'*.php') as $filename) require_once $filename;
foreach (glob(DIR_SYSTEM.'laravel/services/'.'*.php') as $filename) require_once $filename;
foreach (glob(DIR_SYSTEM.'laravel/tasks/'.'*.php') as $filename) require_once $filename;

use App\Eloquent\Encapsulator;

Encapsulator::init();