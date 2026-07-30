<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Interface\Entity;

abstract class BaseEntity implements Entity
{

	public function isNew(): bool
	{
		return !$this->getId();
	}

}
