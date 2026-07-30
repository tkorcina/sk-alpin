<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Enum\ConfigType;
use App\Model\Entity\Trait\TId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Config extends BaseEntity
{
	use TId;

	#[Column(name: '`key`',nullable: false)]
	protected string $key;

	#[Column(name: '`value`', type: \Doctrine\DBAL\Types\Types::TEXT, nullable: false)]
	protected string $value;

	#[Column(name: '`type`', nullable: false, options: ['default' => ConfigType::TYPE_STRING])]
	protected string $type = ConfigType::TYPE_STRING;

	public function setKey(string $key): Config
	{
		$this->key = $key;
		return $this;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function setValue(string $value): Config
	{
		$this->value = $value;
		return $this;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function setType(string $type): Config
	{
		$this->type = $type;
		return $this;
	}

	public function getType(): string
	{
		return $this->type;
	}

}
