<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Trait\TId;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class ContactMessage extends BaseEntity
{
	use TId;

	#[Column(nullable: false)]
	protected string $name = '';

	#[Column(nullable: false)]
	protected string $email = '';

	#[Column(type: Types::TEXT, nullable: false)]
	protected string $message = '';

	#[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
	protected DateTimeImmutable $createdAt;

	public function __construct()
	{
		$this->createdAt = new DateTimeImmutable();
	}

	public function setName(string $name): ContactMessage
	{
		$this->name = $name;
		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setEmail(string $email): ContactMessage
	{
		$this->email = $email;
		return $this;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setMessage(string $message): ContactMessage
	{
		$this->message = $message;
		return $this;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

}
