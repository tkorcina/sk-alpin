<?php
declare(strict_types=1);

namespace App\Model\Query;

interface ContactMessageQueryFactory
{
	function create(): ContactMessageQuery;
}
