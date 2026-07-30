<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Enum\EventType;
use App\Model\Entity\Trait\TId;
use App\Model\Entity\Trait\TPublished;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Nette\Utils\Strings;

#[Entity]
class Event extends BaseEntity
{
	use TId;
	use TPublished;

	#[Column(nullable: false)]
	protected string $title = '';

	#[Column(nullable: false)]
	protected string $slug = '';

	#[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
	protected DateTimeImmutable $dateFrom;

	#[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
	protected ?DateTimeImmutable $dateTo = null;

	#[Column(nullable: false)]
	protected string $place = '';

	#[Column(name: '`type`', nullable: false, options: ['default' => EventType::TYPE_OTHER])]
	protected string $type = EventType::TYPE_OTHER;

	#[Column(type: Types::TEXT, nullable: false)]
	protected string $description = '';

	#[Column(nullable: false, options: ['default' => true])]
	protected bool $isPublic = true;

	public function __construct(DateTimeImmutable $dateFrom)
	{
		$this->dateFrom = $dateFrom;
	}

	public function isInFutureOrNow(): bool
	{
		return ($this->dateTo ?? $this->dateFrom) >= new DateTimeImmutable('today');
	}

	public function getTypeLabel(): string
	{
		return EventType::getLabel($this->type);
	}

	public function setTitle(string $title): Event
	{
		$this->title = $title;
		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setSlug(string $slug): Event
	{
		$this->slug = Strings::webalize($slug);
		return $this;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function setDateFrom(DateTimeImmutable $dateFrom): Event
	{
		$this->dateFrom = $dateFrom;
		return $this;
	}

	public function getDateFrom(): DateTimeImmutable
	{
		return $this->dateFrom;
	}

	public function setDateTo(?DateTimeImmutable $dateTo): Event
	{
		$this->dateTo = $dateTo;
		return $this;
	}

	public function getDateTo(): ?DateTimeImmutable
	{
		return $this->dateTo;
	}

	public function setPlace(string $place): Event
	{
		$this->place = $place;
		return $this;
	}

	public function getPlace(): string
	{
		return $this->place;
	}

	public function setType(string $type): Event
	{
		$this->type = $type;
		return $this;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function setDescription(string $description): Event
	{
		$this->description = $description;
		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setIsPublic(bool $isPublic): Event
	{
		$this->isPublic = $isPublic;
		return $this;
	}

	public function isPublic(): bool
	{
		return $this->isPublic;
	}

}
