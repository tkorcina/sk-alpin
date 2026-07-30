<?php

namespace App\Components\Forms\Config;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\Config;
use App\Model\Entity\Enum\ConfigType;

/**
 * @property Config $entity
 */
class ConfigForm extends BaseForm
{
	protected ?Config $config = null;

	public function initForm(Form $form): void
	{
		$form->addText('key', 'Identifikátor')
			->setRequired();

		$form->addText('value', 'Hodnota')
			->setRequired();

		$form->addSubmit('submit', 'Uložit')
			->setAttribute('class', 'btn btn-dark border-radius-none');

		$form->setDefaults(
			$this->config && !$this->config->isNew()
				? [
					'key' => $this->config->getKey(),
					'value' => $this->config->getValue(),
				] : []
		);
	}

	public function processForm(array $values): void
	{
		if ($this->config && $this->config->isNew()) {
			$this->em->persist($this->config);
		}

		$this->config->setKey($values['key']);

		if (is_numeric($values['value'])) {
			str_replace(',', '.', $values['value']);
		}

		$this->config->setValue($values['value']);
		$this->em->flush($this->config);

		$this->presenter->flashMessageSuccess('Úspěšně uloženo');
		$this->presenter->redirect('config');
	}

	public function setConfig(?Config $item): ConfigForm
	{
		$this->config = $item;
		return $this;
	}

}