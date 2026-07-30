<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ADT\DoctrineAuthenticator\DoctrineAuthenticatorIdentity;
use App\Model\Entity\Trait\TActive;
use App\Model\Entity\Trait\TId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: '`user`')]
class User extends BaseEntity implements DoctrineAuthenticatorIdentity
{
	use TId;
	use TActive;

	#[Column(nullable: false)]
	protected string $firstName;

	#[Column(nullable: false)]
	protected string $lastName;

	#[Column(unique: true, nullable: false)]
	protected string $email;

	#[Column(unique: true, nullable: true)]
	protected ?string $phoneNumber = null;

	#[Column(nullable: true)]
	protected ?string $password = null;

	#[Column(nullable: true)]
	protected ?string $resetPasswordHash = null;

	public function getRoles(): array
	{
		return [];
	}

	public function getAuthObjectId(): string
	{
		return (string) $this->getId();
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): static
	{
		$this->email = $email;
		return $this;
	}

	public function getPhoneNumber(): ?string
	{
		return $this->phoneNumber;
	}

	public function setPhoneNumber(string $phoneNumber): static
	{
		$this->phoneNumber = $phoneNumber;
		return $this;
	}

	public function setFirstName(string $firstName): static
	{
		$this->firstName = $firstName;
		return $this;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function setLastName(string $lastName): static
	{
		$this->lastName = $lastName;
		return $this;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function getFullName(): string
	{
		return $this->firstName . ' ' . $this->lastName;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(?string $password): User
	{
		$this->password = $password;
		return $this;
	}

	public function getResetPasswordHash(): ?string
	{
		return $this->resetPasswordHash;
	}

	public function setResetPasswordHash(?string $resetPasswordHash): User
	{
		$this->resetPasswordHash = $resetPasswordHash;
		return $this;
	}

}
