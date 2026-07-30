<?php
declare(strict_types=1);

namespace App\Model\Query;

interface NewsQueryFactory
{
	function create(): NewsQuery;
}
