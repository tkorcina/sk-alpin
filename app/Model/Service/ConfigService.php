<?php
declare(strict_types=1);


namespace App\Model\Service;

use App\Model\Database\EntityManager;
use App\Model\Entity\Config;
use App\Model\Query\ConfigQueryFactory;
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

	public function getConfigValue(string $key, $locale = 'cs'): ?string
	{
		try {
			$val = $this->configQueryFactory->create()->byKey($key)->fetchOne();

			return $locale === 'en'
				? ($val->getValueEn() ?: $val->getValue())
				: $val->getValue();

		} catch (NoResultException $e) {
			return null;
		}
	}

	public function getConfigValueAsFloat(string $key): float
	{
		try {
			$value = $this->configQueryFactory->create()->byKey($key)->fetchOne()?->getValue();

			if (Strings::contains($value, ',')) {
				$value = str_replace(',', '.', $value);
			}

			return (float) $value;

		} catch (NoResultException $e) {
			return 0;
		}
	}


}