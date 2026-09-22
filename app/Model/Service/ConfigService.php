<?php
declare(strict_types=1);


namespace App\Model\Service;

use App\Model\Database\EntityManager;
use App\Model\Entity\Config;
use App\Model\Entity\Enum\ConfigKey;
use App\Model\Entity\Enum\ConfigType;
use App\Model\Entity\File;
use App\Model\Query\ConfigQueryFactory;
use App\Model\SiteSettings;
use Doctrine\ORM\NoResultException;
use Nette\Utils\Strings;

class ConfigService extends BaseService
{

	public function __construct(
		protected EntityManager $em,
		protected ConfigQueryFactory $configQueryFactory,
	) {
		parent::__construct($this->em);
	}

	public function getConfig(string $key): ?Config
	{
		try {
			return $this->configQueryFactory->create()->byKey($key)->fetchOne();
		} catch (NoResultException $e) {
			return null;
		}
	}

	public function getConfigValue(string $key): ?string
	{
		return $this->getConfig($key)?->getValue();
	}

	public function getConfigValueAsFloat(string $key): float
	{
		$value = $this->getConfigValue($key);

		if ($value === null) {
			return 0;
		}

		if (Strings::contains($value, ',')) {
			$value = str_replace(',', '.', $value);
		}

		return (float) $value;
	}

	/**
	 * Hodnota typu TYPE_FILE — vrací aktivní entitu File, nebo null.
	 */
	public function getConfigFile(string $key): ?File
	{
		$value = $this->getConfigValue($key);

		if (!$value) {
			return null;
		}

		$file = $this->em->getRepository(File::class)->find((int) $value);

		return $file && $file->isActive() ? $file : null;
	}

	/**
	 * Uloží hodnotu; záznam založí, pokud neexistuje. Neflushuje.
	 */
	public function setConfigValue(string $key, ?string $value, string $type = ConfigType::TYPE_STRING): Config
	{
		$config = $this->getConfig($key);

		if (!$config) {
			$config = (new Config)->setKey($key);
			$this->em->persist($config);
		}

		$config
			->setValue($value ?? '')
			->setType($type);

		return $config;
	}

	/**
	 * Nastaví soubor (logo, hero obrázek). Původní soubor deaktivuje. Neflushuje.
	 */
	public function setConfigFile(string $key, ?File $file): Config
	{
		$this->getConfigFile($key)?->setActive(false);

		return $this->setConfigValue($key, $file ? (string) $file->getId() : '', ConfigType::TYPE_FILE);
	}

	public function getSiteSettings(): SiteSettings
	{
		return new SiteSettings(
			siteTitle: $this->getConfigValue(ConfigKey::SITE_TITLE) ?? 'SK-Alpin',
			motto: $this->getConfigValue(ConfigKey::MOTTO) ?? '',
			description: $this->getConfigValue(ConfigKey::DESCRIPTION) ?? '',
			companyName: $this->getConfigValue(ConfigKey::COMPANY_NAME) ?? '',
			companyAddress: $this->getConfigValue(ConfigKey::COMPANY_ADDRESS) ?? '',
			companyIco: $this->getConfigValue(ConfigKey::COMPANY_ICO) ?? '',
			logo: $this->getConfigFile(ConfigKey::LOGO),
			heroImage: $this->getConfigFile(ConfigKey::HERO_IMAGE),
		);
	}

}
