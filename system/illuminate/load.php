<?php

require_once(DIR_SYSTEM.'illuminate/vendor/autoload.php');

// foreach (glob(DIR_SYSTEM.'illuminate/core/'.'*.php') as $filename) require_once $filename;

require_once(DIR_SYSTEM.'illuminate/core/Encapsulator.php');
require_once(DIR_SYSTEM.'illuminate/core/EncapsulatedEloquentBase.php');
require_once(DIR_SYSTEM.'illuminate/core/AbstractValidator.php');

foreach (glob(DIR_SYSTEM.'illuminate/models/'.'*.php') as $filename) require_once $filename;
foreach (glob(DIR_SYSTEM.'illuminate/validators/'.'*.php') as $filename) require_once $filename;