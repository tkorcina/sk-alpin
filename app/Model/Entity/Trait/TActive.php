<?php
declare(strict_types=1);


namespace App\Model\Entity\Trait;

use Doctrine\ORM\Mapping\Column;

trait TActive
{

	#[Column(nullable: false, options: ['default' => true])]
	protected bool $active = true;

	public function setActive(bool $active): static
	{
		$this->active = $active;
		return $this;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

}