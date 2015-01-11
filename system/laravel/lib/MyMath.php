<?php

class MyMath
{
	static public function percent($numerator, $denominator)
	{
		return $denominator == 0 ? '無' : number_format($numerator / $denominator * 100, 2).'%';
	}
}