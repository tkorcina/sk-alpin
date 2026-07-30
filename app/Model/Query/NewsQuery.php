<?php
declare(strict_types=1);

namespace App\Model\Query;

use App\Model\Entity\News;
use Closure;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseQuery<News>
 */
class NewsQuery extends BaseQuery
{
	protected ?string $entityClass = News::class;

	protected function getDefaultOrder(): ?Closure
	{
		return fn (QueryBuilder $qb) => $qb
			->orderBy('e.publishedAt', 'DESC');
	}

	public function byIsPublished(bool $bool = true): static
	{
		return $this->by('published', $bool);
	}

	public function bySlug(string $slug): static
	{
		return $this->by('slug', $slug);
	}

}
