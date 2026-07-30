<?php

declare(strict_types=1);

namespace App\Model\Entity;

use ADT\DoctrineSessionHandler\SessionInterface;
use ADT\DoctrineSessionHandler\SessionTrait;
use App\Model\Entity\Trait\TId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\UniqueConstraint(name: 'sessionId', fields: ['sessionId'])]
#[ORM\Entity]
class SessionStorage extends BaseEntity implements SessionInterface
{
	use SessionTrait;
	use TId;
}
