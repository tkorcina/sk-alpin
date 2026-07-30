<?php
declare(strict_types=1);

namespace App\Model\Query;

use App\Model\Entity\Event;
use App\Model\Entity\Gallery;
use Closure;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseQuery<Gallery>
 */
class GalleryQuery extends BaseQuery
{
	protected ?string $entityClass = Gallery::class;

	protected function getDefaultOrder(): ?Closure
	{
		return fn (QueryBuilder $qb) => $qb
			->orderBy('e.date', 'DESC');
	}

	public function byIsPublished(bool $bool = true): static
	{
		return $this->by('published', $bool);
	}

	public function bySlug(string $slug): static
	{
		return $this->by('slug', $slug);
	}

	public function byEvent(Event $event): static
	{
		return $this->by('event', $event);
	}

}
