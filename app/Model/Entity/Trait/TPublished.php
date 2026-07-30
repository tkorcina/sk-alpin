<?php
declare(strict_types=1);


namespace App\Model\Entity\Trait;

use Doctrine\ORM\Mapping\Column;

trait TPublished
{

	#[Column(nullable: false, options: ['default' => false])]
	protected bool $published = false;

	public function setPublished(bool $published): static
	{
		$this->published = $published;
		return $this;
	}

	public function isPublished(): bool
	{
		return $this->published;
	}

}
