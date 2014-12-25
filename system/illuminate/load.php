<?php

// Eloquent model
require_once(DIR_SYSTEM.'illuminate/vendor/autoload.php');
require_once(DIR_SYSTEM.'illuminate/eloquent/Encapsulator.php');
require_once(DIR_SYSTEM.'illuminate/eloquent/EncapsulatedEloquentBase.php');
$eloquent = DIR_SYSTEM.'illuminate/eloquent/models/';
foreach (glob($eloquent.'*.php') as $filename) require_once $filename;