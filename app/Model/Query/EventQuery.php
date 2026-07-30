<?php
declare(strict_types=1);

namespace App\Model\Query;

use App\Model\Entity\Event;
use Closure;
use DateTimeImmutable;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseQuery<Event>
 */
class EventQuery extends BaseQuery
{
	protected ?string $entityClass = Event::class;

	protected function getDefaultOrder(): ?Closure
	{
		return fn (QueryBuilder $qb) => $qb
			->orderBy('e.dateFrom', 'ASC');
	}

	public function byIsPublished(bool $bool = true): static
	{
		return $this->by('published', $bool);
	}

	public function byIsPublic(bool $bool = true): static
	{
		return $this->by('isPublic', $bool);
	}

	public function bySlug(string $slug): static
	{
		return $this->by('slug', $slug);
	}

	public function byType(string $type): static
	{
		return $this->by('type', $type);
	}

	public function byUpcoming(): static
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->andWhere('COALESCE(e.dateTo, e.dateFrom) >= :today')
				->setParameter('today', new DateTimeImmutable('today'));
		};
		return $this;
	}

	public function byPast(): static
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->andWhere('COALESCE(e.dateTo, e.dateFrom) < :today')
				->setParameter('today', new DateTimeImmutable('today'));
		};
		return $this;
	}

}
