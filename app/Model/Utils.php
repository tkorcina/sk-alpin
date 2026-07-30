<?php
declare(strict_types=1);


namespace App\Model;

class Utils
{

	public static function fillParameters($html, array $parameters): string
	{
		$params = [];
		foreach ($parameters as $key => $value) {
			$params["%" . $key . "%"] = $value;
		}
		return str_replace(array_keys($params), array_values($params), $html);
	}

}