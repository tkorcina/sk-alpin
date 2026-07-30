<?php
declare(strict_types=1);

namespace App\Model\Query;

interface ConfigQueryFactory
{
	function create(): ConfigQuery;
}
