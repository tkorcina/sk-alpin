<?php
declare(strict_types=1);

namespace App\Model\Query;

use App\Model\Entity\ContactMessage;
use Closure;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseQuery<ContactMessage>
 */
class ContactMessageQuery extends BaseQuery
{
	protected ?string $entityClass = ContactMessage::class;

	protected function getDefaultOrder(): ?Closure
	{
		return fn (QueryBuilder $qb) => $qb
			->orderBy('e.createdAt', 'DESC');
	}

}
