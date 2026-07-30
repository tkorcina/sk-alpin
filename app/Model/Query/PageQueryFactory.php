<?php
declare(strict_types=1);

namespace App\Model\Query;

interface PageQueryFactory
{
	function create(): PageQuery;
}
