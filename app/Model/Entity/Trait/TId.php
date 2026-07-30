<?php declare(strict_types = 1);

namespace App\Model\Entity\Trait;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

trait TId
{
	#[Id]
	#[Column(nullable: false)]
	#[GeneratedValue(strategy: "IDENTITY")]
	private ?int $id = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId($id): static
	{
		$this->id = $id;
		return $this;
	}


	public function __clone()
	{
		$this->id = null;
	}

}