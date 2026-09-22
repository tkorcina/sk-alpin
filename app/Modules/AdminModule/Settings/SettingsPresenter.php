<?php

namespace App\Modules\AdminModule\Settings;

use App\Components\Forms\Settings\SettingsForm;
use App\Components\Forms\Settings\SettingsFormFactory;
use App\Model\Entity\Enum\ConfigKey;
use App\Modules\AdminModule\BasePresenter;

class SettingsPresenter extends BasePresenter
{

	public function actionDefault(): void
	{}

	public function createComponentSettingsForm(SettingsFormFactory $factory): SettingsForm
	{
		return $factory->create();
	}

	public function handleDeleteFile(string $key): void
	{
		if (!in_array($key, [ConfigKey::LOGO, ConfigKey::HERO_IMAGE], true)) {
			$this->error();
		}

		if ($this->configService->getConfigFile($key)) {
			$this->configService->setConfigFile($key, null);
			$this->em->flush();
			$this->flashMessageSuccess('Obrázek úspěšně smazán');
		}

		$this->redirect('default');
	}

}
