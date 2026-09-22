<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Entity\File;

/**
 * Nastavení webu načtené z tabulky `config` (viz ConfigService::getSiteSettings()).
 * Používá se v šablonách veřejné části jako $settings.
 */
final class SiteSettings
{

	public function __construct(
		private string $siteTitle,
		private string $motto,
		private string $description,
		private string $companyName,
		private string $companyAddress,
		private string $companyIco,
		private ?File $logo,
		private ?File $heroImage,
	) {}

	public function getSiteTitle(): string
	{
		return $this->siteTitle;
	}

	public function getMotto(): string
	{
		return $this->motto;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getCompanyName(): string
	{
		return $this->companyName;
	}

	public function getCompanyAddress(): string
	{
		return $this->companyAddress;
	}

	public function getCompanyIco(): string
	{
		return $this->companyIco;
	}

	public function getLogo(): ?File
	{
		return $this->logo;
	}

	public function getHeroImage(): ?File
	{
		return $this->heroImage;
	}

}
