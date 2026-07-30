<?php
declare(strict_types=1);

namespace App\Model\Query;

use App\Model\Entity\Config;
use Closure;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseQuery<Config>
 */
class ConfigQuery extends BaseQuery
{
	protected ?string $entityClass = Config::class;

	protected function getDefaultOrder(): ?Closure
	{
		return fn (QueryBuilder $qb) => $qb
			->orderBy('e.key', 'ASC');
	}

	public function byKey(string $key): ConfigQuery
	{
		return $this->by('key', $key);
	}

}