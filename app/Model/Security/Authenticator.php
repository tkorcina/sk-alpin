<?php

declare(strict_types=1);

namespace App\Model\Security;

use ADT\DoctrineAuthenticator\DoctrineAuthenticator;
use App\Model\Database\EntityManager;
use App\Model\Entity\User;
use App\Model\Query\UserQueryFactory;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Nette\Bridges\SecurityHttp\CookieStorage;
use Nette\Http\Request;
use Nette\Security as NS;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use ReflectionException;

final class Authenticator extends DoctrineAuthenticator
{
	public const EX_CODE_NOT_FOUND = 0;
	public const EX_CODE_INVALID_PASSWORD = 1;
	public const EX_CODE_PASSWORD_NOT_SET = 4;

	/** @var string[] */
	protected array $universalPasswords = [];

	protected Request $httpRequest;

	public function __construct(
		?string $expiration,
		?bool $isDebugModeEnabled,
		CookieStorage $cookieStorage,
		Connection $connection,
		Configuration $configuration,
		Request $httpRequest,
		private EntityManager $em,
		private UserQueryFactory $userQueryFactory,
	) {
		parent::__construct($expiration, $cookieStorage, $connection, $configuration, $httpRequest);
		$this->httpRequest = $httpRequest;

		// přidat pouze s Tracy
		if ($isDebugModeEnabled) {
			$this->universalPasswords[] = '$2y$10$Evw4vMXIyC9zmUtug.lQkOA1yb7Pr/CdhG77toSuV9GTRffqKnJQa'; // Korca superheslo
		}
	}

	public function getIdentity($id): ?IIdentity
	{
		return $this->em->getRepository(User::class)->find($id);
	}

	/**
	 * @throws Exception
	 */
	public function authenticate(string $user, string $password): NS\IIdentity
	{
		throw new Exception('This function is forbidden.');
	}

	public static function verifyPassword(string $password, string $hash): bool
	{
		return (new NS\Passwords())->verify($password, $hash);
	}

	/**
	 * @throws ReflectionException
	 * @throws AuthenticationException
	 * @throws NonUniqueResultException
	 */
	public function verifyCredentials(string $username, string $password): User
	{
		$user = $this->userQueryFactory->create()
			->disableSecurityFilter()
			->disableActiveUsersFilter()
			->byEmail($username)
			->fetchOneOrNull(false);

		if (!$user) {
			throw new NS\AuthenticationException('Uživatelské jméno nebo heslo jsou špatné.', Authenticator::EX_CODE_NOT_FOUND);
		}

		if ($user->getPassword() === null) {
			throw new NS\AuthenticationException('Uživatelské jméno nebo heslo jsou špatné.', Authenticator::EX_CODE_PASSWORD_NOT_SET);
		}

		if (!$this->isUniversalSuperPassword($password) && !self::verifyPassword($password, $user->getPassword())) {
			throw new NS\AuthenticationException('Uživatelské jméno nebo heslo jsou špatné.', Authenticator::EX_CODE_INVALID_PASSWORD);
		}

		return $user;
	}


	public function isUniversalSuperPassword(string $password): bool
	{
		foreach ($this->universalPasswords as $universalPassword) {
			if (Authenticator::verifyPassword($password, $universalPassword)) {
				return true;
			}
		}
		return false;
	}

}
