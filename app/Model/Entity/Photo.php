<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Trait\TId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Photo extends BaseEntity
{
	use TId;

	#[ManyToOne(targetEntity: Gallery::class, inversedBy: 'photos')]
	#[JoinColumn(nullable: false, onDelete: 'CASCADE')]
	protected Gallery $gallery;

	#[ManyToOne(targetEntity: File::class)]
	#[JoinColumn(nullable: false)]
	protected File $file;

	#[Column(nullable: false, options: ['default' => 0])]
	protected int $sort = 0;

	#[Column(nullable: true)]
	protected ?string $description = null;

	public function __construct(Gallery $gallery, File $file)
	{
		$this->gallery = $gallery;
		$this->file = $file;
	}

	public function setGallery(Gallery $gallery): Photo
	{
		$this->gallery = $gallery;
		return $this;
	}

	public function getGallery(): Gallery
	{
		return $this->gallery;
	}

	public function setFile(File $file): Photo
	{
		$this->file = $file;
		return $this;
	}

	public function getFile(): File
	{
		return $this->file;
	}

	public function setSort(int $sort): Photo
	{
		$this->sort = $sort;
		return $this;
	}

	public function getSort(): int
	{
		return $this->sort;
	}

	public function setDescription(?string $description): Photo
	{
		$this->description = $description;
		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

}
