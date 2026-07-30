<?php
declare(strict_types=1);

namespace App\Model\Query;

use ADT\DoctrineComponents\QueryObjectByMode;
use App\Model\Entity\AclRole;
use App\Model\Entity\Company;
use App\Model\Entity\Country;
use App\Model\Entity\RegionZip;
use App\Model\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Closure;

/**
 * @extends BaseQuery<User>
 */
class UserQuery extends BaseQuery
{
	protected ?string $entityClass = User::class;

	protected function getDefaultOrder(): ?Closure
	{
		return fn (QueryBuilder $qb) => $qb
			->orderBy('e.lastName', 'ASC')
			->addOrderBy('e.firstName', 'ASC');
	}

	public function byEmail(string $email): static
	{
		$this->filter[] = function(QueryBuilder $qb) use ($email) {
			$qb->andWhere('e.email = :email')
				->setParameter('email', $email);
		};
		return $this;
	}

	public function byPhoneNumber(string $phoneNumber): static
	{
		$this->filter[] = function(QueryBuilder $qb) use ($phoneNumber) {
			$qb->andWhere('e.phoneNumber = :phoneNumber')
				->setParameter('phoneNumber', $phoneNumber);
		};
		return $this;
	}

	public function byQuery(string $query): static
	{
		return $this->by(['firstName', 'lastName'], $query, QueryObjectByMode::CONTAINS);
	}
}