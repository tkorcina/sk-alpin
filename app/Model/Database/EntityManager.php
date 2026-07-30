<?php declare(strict_types = 1);

namespace App\Model\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Nettrine\ORM\EntityManagerDecorator;

class EntityManager extends EntityManagerDecorator
{

	/**
	 * @throws Exception
	 */
	public function getConnectionByParams($params): Connection
	{
		return DriverManager::getConnection($params);
	}

}