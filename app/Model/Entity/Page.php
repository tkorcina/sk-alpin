<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Trait\TId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Page extends BaseEntity
{
	use TId;

	#[Column(unique: true, nullable: false)]
	protected string $internalName;

	#[Column(nullable: false)]
	protected string $title = '';

	#[Column(type: Types::TEXT, nullable: false)]
	protected string $content = '';

	#[ManyToOne(targetEntity: File::class)]
	#[JoinColumn(nullable: true)]
	protected ?File $image = null;

	public function __construct(string $internalName)
	{
		$this->internalName = $internalName;
	}

	public function getInternalName(): string
	{
		return $this->internalName;
	}

	public function isDeletable(): bool
	{
		return false;
	}

	public function setTitle(string $title): Page
	{
		$this->title = $title;
		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setContent(string $content): Page
	{
		$this->content = $content;
		return $this;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setImage(?File $image): Page
	{
		$this->image = $image;
		return $this;
	}

	public function getImage(): ?File
	{
		return $this->image;
	}

}
