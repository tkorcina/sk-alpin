<?php

declare(strict_types=1);

namespace App\Components\Forms\Settings;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\Enum\ConfigKey;
use App\Model\Entity\File;
use App\Model\Service\ConfigService;
use App\Model\SiteSettings;
use Kdyby\Autowired\Attributes\Autowire;
use Nette\Http\FileUpload;

/**
 * Nastavení webu — jeden formulář s pevnými poli, hodnoty se ukládají do tabulky `config` podle klíčů z ConfigKey.
 */
class SettingsForm extends BaseForm
{
	#[Autowire]
	protected ConfigService $configService;

	protected ?SiteSettings $settings = null;

	public function initForm(Form $form): void
	{
		$settings = $this->getSettings();


		$form->addText(ConfigKey::SITE_TITLE, 'Název webu*')
			->setOption('description', 'Zobrazuje se v titulku okna prohlížeče (např. „Akce | SK-Alpin“).')
			->setRequired('Vyplňte název webu');

		$form->addTextArea(ConfigKey::MOTTO, 'Motto')
			->setHtmlAttribute('rows', 2)
			->setOption('description', 'Text v úvodním bloku hlavní stránky nad tlačítkem „Podívejte se na naše akce“.');

		$form->addTextArea(ConfigKey::DESCRIPTION, 'Popisek webu')
			->setHtmlAttribute('rows', 3)
			->setOption('description', 'Krátký text o spolku — zobrazuje se na hlavní stránce pod úvodním blokem a zároveň jako popis webu pro vyhledávače.');

		$form->addGroup('Údaje spolku (patička)');

		$form->addText(ConfigKey::COMPANY_NAME, 'Název spolku*')
			->setRequired('Vyplňte název spolku');

		$form->addText(ConfigKey::COMPANY_ADDRESS, 'Adresa');

		$form->addText(ConfigKey::COMPANY_ICO, 'IČO');

		$form->addText(ConfigKey::ANALYTICS_ID, 'Google Analytics — Measurement ID')
			->setHtmlAttribute('placeholder', 'G-XXXXXXXXXX')
			->setOption('description', 'Nechte prázdné, pokud návštěvnost neměříte. Když je vyplněno, měření se načte až po souhlasu návštěvníka v cookie liště.')
			->addCondition(\Nette\Forms\Form::Filled)
				->addRule(\Nette\Forms\Form::Pattern, 'Measurement ID má tvar G-XXXXXXXXXX.', '(G|UA|GT)-[A-Z0-9-]+');


		$form->addUpload(ConfigKey::LOGO, 'Logo')
			->setOption('description', 'Když není nahrané, použije se výchozí logo webu. Ideálně PNG/SVG s průhledným pozadím, výška cca 50 px.')
			->addRule(\Nette\Forms\Form::MimeType, 'Lze nahrát pouze soubory typu: ' . implode(', ', static::$imageExtensions) . ', .svg', [...static::$imageMimeTypes, 'image/svg+xml'])
			->addRule(\Nette\Forms\Form::MaxFileSize, 'Maximální velikost nahrávaného souboru je 10 MB.', 10 * 1024 * 1024);

		$form->addUpload(ConfigKey::HERO_IMAGE, 'Úvodní obrázek')
			->setOption('description', 'Velká fotka v úvodním bloku hlavní stránky (doporučeno min. 1600 px na šířku). Když není nahraná, použije se barevný přechod.')
			->addRule(\Nette\Forms\Form::MimeType, 'Lze nahrát pouze soubory typu: ' . implode(', ', static::$imageExtensions), static::$imageMimeTypes)
			->addRule(\Nette\Forms\Form::MaxFileSize, 'Maximální velikost nahrávaného souboru je 10 MB.', 10 * 1024 * 1024);


		$form->addSubmit('submit', 'Uložit')
			->setAttribute('class', 'btn btn-dark border-radius-none');

		$form->setDefaults([
			ConfigKey::SITE_TITLE => $settings->getSiteTitle(),
			ConfigKey::MOTTO => $settings->getMotto(),
			ConfigKey::DESCRIPTION => $settings->getDescription(),
			ConfigKey::COMPANY_NAME => $settings->getCompanyName(),
			ConfigKey::COMPANY_ADDRESS => $settings->getCompanyAddress(),
			ConfigKey::COMPANY_ICO => $settings->getCompanyIco(),
			ConfigKey::ANALYTICS_ID => $settings->getAnalyticsId(),
		]);
	}

	public function processForm(array $values): void
	{
		foreach ([
			ConfigKey::SITE_TITLE,
			ConfigKey::MOTTO,
			ConfigKey::DESCRIPTION,
			ConfigKey::COMPANY_NAME,
			ConfigKey::COMPANY_ADDRESS,
			ConfigKey::COMPANY_ICO,
			ConfigKey::ANALYTICS_ID,
		] as $key) {
			$this->configService->setConfigValue($key, trim((string) $values[$key]));
		}

		foreach ([ConfigKey::LOGO, ConfigKey::HERO_IMAGE] as $key) {
			/** @var FileUpload $upload */
			$upload = $values[$key];

			if (!$upload || !$upload->isOk()) {
				continue;
			}

			$file = $this->fileService->saveImage($upload, 's_' . $key);

			if ($file instanceof File) {
				$this->configService->setConfigFile($key, $file);
			} elseif (is_string($file)) {
				$this->presenter->flashMessageError($file);
				$this->presenter->redirect('this');
			}
		}

		$this->em->flush();

		$this->presenter->flashMessageSuccess('Nastavení úspěšně uloženo');
		$this->presenter->redirect('this');
	}

	protected function getSettings(): SiteSettings
	{
		return $this->settings ??= $this->configService->getSiteSettings();
	}

	public function render(): void
	{
		$this->template->settings = $this->getSettings();
		parent::render();
	}

}
