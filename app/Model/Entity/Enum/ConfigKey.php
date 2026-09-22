<?php
declare(strict_types=1);


namespace App\Model\Entity\Enum;

/**
 * Klíče záznamů v tabulce `config` — nastavení webu editovatelné v administraci (sekce Nastavení).
 */
class ConfigKey
{

	/** Název webu (title v prohlížeči) */
	const SITE_TITLE = 'siteTitle';

	/** Motto — text v hero bloku na úvodní stránce */
	const MOTTO = 'motto';

	/** Popisek webu — úvodní odstavec na homepage + meta description */
	const DESCRIPTION = 'description';

	/** Oficiální název spolku (patička) */
	const COMPANY_NAME = 'companyName';

	/** Adresa sídla (patička) */
	const COMPANY_ADDRESS = 'companyAddress';

	/** IČO (patička) */
	const COMPANY_ICO = 'companyIco';

	/** Logo (File) — když není, použije se výchozí SVG */
	const LOGO = 'logo';

	/** Úvodní (hero) obrázek na homepage (File) — když není, použije se barevný přechod */
	const HERO_IMAGE = 'heroImage';

}
