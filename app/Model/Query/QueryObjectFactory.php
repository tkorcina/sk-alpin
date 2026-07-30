<?php

namespace App\Model\Query;

interface QueryObjectFactory
{
	public function create(): BaseQuery;
}
