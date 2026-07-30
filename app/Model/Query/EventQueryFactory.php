<?php
declare(strict_types=1);

namespace App\Model\Query;

interface EventQueryFactory
{
	function create(): EventQuery;
}
