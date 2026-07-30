<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Trait\TId;
use App\Model\Entity\Trait\TPublished;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Nette\Utils\Strings;

#[Entity]
class News extends BaseEntity
{
	use TId;
	use TPublished;

	#[Column(nullable: false)]
	protected string $title = '';

	#[Column(nullable: false)]
	protected string $slug = '';

	#[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
	protected DateTimeImmutable $publishedAt;

	#[Column(type: Types::TEXT, nullable: false)]
	protected string $content = '';

	#[ManyToOne(targetEntity: File::class)]
	#[JoinColumn(nullable: true)]
	protected ?File $image = null;

	public function __construct(DateTimeImmutable $publishedAt)
	{
		$this->publishedAt = $publishedAt;
	}

	public function setTitle(string $title): News
	{
		$this->title = $title;
		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setSlug(string $slug): News
	{
		$this->slug = Strings::webalize($slug);
		return $this;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function setPublishedAt(DateTimeImmutable $publishedAt): News
	{
		$this->publishedAt = $publishedAt;
		return $this;
	}

	public function getPublishedAt(): DateTimeImmutable
	{
		return $this->publishedAt;
	}

	public function setContent(string $content): News
	{
		$this->content = $content;
		return $this;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setImage(?File $image): News
	{
		$this->image = $image;
		return $this;
	}

	public function getImage(): ?File
	{
		return $this->image;
	}

}
