<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Trait\TActive;
use App\Model\Entity\Trait\TId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class File extends BaseEntity
{
	use TId;
	use TActive;

	#[Column(nullable: false)]
	protected string $fileName;

	#[Column(nullable: false)]
	protected string $path;

	#[Column(nullable: true)]
	protected ?string $contentType = null;

	#[Column(name: '`type`', nullable: true)]
	protected ?string $type = null;

	public function setFileName(string $fileName): File
	{
		$this->fileName = $fileName;
		return $this;
	}

	public function getFileName(): string
	{
		return $this->fileName;
	}

	public function setPath(string $path): File
	{
		$this->path = $path;
		return $this;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function setContentType(?string $contentType = null): File
	{
		$this->contentType = $contentType;
		return $this;
	}

	public function getContentType(): ?string
	{
		return $this->contentType;
	}

	public function setType(?string $type): File
	{
		$this->type = $type;
		return $this;
	}

	public function getType(): ?string
	{
		return $this->type;
	}

}
