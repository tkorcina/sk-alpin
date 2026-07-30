<?php
declare(strict_types=1);


namespace App\Model\Service;

use App\Model\Database\EntityManager;

abstract class BaseService
{
	public function __construct(
		protected EntityManager $em
	)
	{}
}