<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Trait\TId;
use App\Model\Entity\Trait\TPublished;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Nette\Utils\Strings;

#[Entity]
class Gallery extends BaseEntity
{
	use TId;
	use TPublished;

	#[Column(nullable: false)]
	protected string $title = '';

	#[Column(nullable: false)]
	protected string $slug = '';

	#[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
	protected ?DateTimeImmutable $date = null;

	#[ManyToOne(targetEntity: Event::class)]
	#[JoinColumn(nullable: true, onDelete: 'SET NULL')]
	protected ?Event $event = null;

	#[ManyToOne(targetEntity: File::class)]
	#[JoinColumn(nullable: true)]
	protected ?File $mainImage = null;

	#[OneToMany(mappedBy: 'gallery', targetEntity: Photo::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
	#[OrderBy(['sort' => 'ASC'])]
	protected Collection $photos;

	public function __construct()
	{
		$this->photos = new ArrayCollection();
	}

	public function setTitle(string $title): Gallery
	{
		$this->title = $title;
		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setSlug(string $slug): Gallery
	{
		$this->slug = Strings::webalize($slug);
		return $this;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function setDate(?DateTimeImmutable $date): Gallery
	{
		$this->date = $date;
		return $this;
	}

	public function getDate(): ?DateTimeImmutable
	{
		return $this->date;
	}

	public function setEvent(?Event $event): Gallery
	{
		$this->event = $event;
		return $this;
	}

	public function getEvent(): ?Event
	{
		return $this->event;
	}

	public function setMainImage(?File $mainImage): Gallery
	{
		$this->mainImage = $mainImage;
		return $this;
	}

	public function getMainImage(): ?File
	{
		if ($this->mainImage) {
			return $this->mainImage;
		}
		$photos = $this->getPhotos();
		return $photos ? reset($photos)->getFile() : null;
	}

	/**
	 * @return Photo[]
	 */
	public function getPhotos(): array
	{
		return $this->photos->toArray();
	}

	public function addPhoto(Photo $photo): static
	{
		if (!$this->photos->contains($photo)) {
			$this->photos->add($photo);
			$photo->setGallery($this);
		}
		return $this;
	}

	public function removePhoto(Photo $photo): static
	{
		$this->photos->removeElement($photo);
		return $this;
	}

}
