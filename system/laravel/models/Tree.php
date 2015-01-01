<?php 

use Baum\Node;
use Baum\BaumServiceProvider;
use App\Eloquent\Encapsulator;

class Tree extends Node
{
	protected $orderColumn = 'position';

    protected $softDelete = false;
}