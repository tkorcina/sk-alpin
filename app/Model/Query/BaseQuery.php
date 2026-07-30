<?php
declare(strict_types=1);


namespace App\Model\Query;

use ADT\DoctrineComponents\QueryObject;
use App\Model\Security\SecurityUser;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends QueryObject<TEntity>
 * @template TEntity of object
 */
class BaseQuery extends QueryObject
{
	const FILTER_SECURITY = 'filter_security';
	const FILTER_IS_ACTIVE = 'filter_is_active';

	protected ?string $entityClass = null;

	protected SecurityUser $securityUser;

	public function getEntityClass(): string
	{
		if ($this->entityClass) {
			return $this->entityClass;
		}

		$fullClassQueryName = get_class($this);
		$fullClassEntityName = str_replace('App\Model\Query', 'App\Model\Entity', $fullClassQueryName);
		return str_replace('Query', '', $fullClassEntityName);
	}

	public function setSecurityUser(SecurityUser $securityUser): static
	{
		$this->securityUser = $securityUser;
		return $this;
	}

	protected function setDefaultOrder(): void
	{
		if (method_exists($this, 'getDefaultOrder')) {
			$this->order = $this->getDefaultOrder();
		} else {
			$this->orderBy('id', 'ASC');
		}
	}

	public function setEntityClass(?string $entityClass): void
	{
		$this->entityClass = $entityClass;
	}

	public function fetchPairs(?string $value = 'name', ?string $key = 'id'): array
	{
		return parent::fetchPairs($value, $key);
	}

	public function byNotId(int|array|null $id): static
	{
		if ($id) {
			$this->filter[] = function(QueryBuilder $qb) use ($id) {
				$qb->andWhere('e.id NOT IN (:id)')
					->setParameter('id', $id);
			};
		}
		return $this;
	}

	public function applyPaging(?int $limit = null, ?int $offset = null): static
	{
		$this->filter[] = function (QueryBuilder $qb) use ($limit, $offset) {
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		};
		return $this;
	}

	public function disableSecurityFilter(): static
	{
		unset($this->filter[self::FILTER_SECURITY]);
		return $this;
	}

	public function disableActiveUsersFilter(): static
	{
		unset($this->filter[self::FILTER_IS_ACTIVE]);
		return $this;
	}

	public function byId($id): static
	{
		parent::byId($id);
		$this->disableActiveUsersFilter();
		return $this;
	}
}