<?php

namespace App\Library\Utils;

use ReflectionClass;

abstract class BagOfConstants
{
	public static function all()
	{
		$constant_values = array_values((new ReflectionClass(static::class))->getConstants());

		return array_filter($constant_values, function ($value) {
			return !is_array($value) && !is_object($value);
		});
	}

	public static function allJoin()
	{
		return implode(',', static::all());
	}
}
