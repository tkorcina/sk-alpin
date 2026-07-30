<?php

declare(strict_types=1);

namespace App\Components\Forms\Event;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\Enum\EventType;
use App\Model\Entity\Event;

class EventForm extends BaseForm
{
	protected ?Event $item = null;

	public function initForm(Form $form): void
	{
		$form->addText('title', 'Název*')
			->setRequired('Vyplňte název');

		$form->addText('place', 'Místo*')
			->setRequired('Vyplňte místo');

		$form->addSelect('type', 'Typ akce*', EventType::getLabels())
			->setRequired('Vyberte typ akce');

		$form->addDate('dateFrom', 'Datum od*')
			->setRequired('Vyplňte datum začátku');

		$form->addDate('dateTo', 'Datum do')
			->setOption('description', 'nepovinné')
			->setRequired(false);

		$form->addTextArea('description', 'Popis*')
			->setHtmlAttribute('class', 'editor')
			->setRequired('Vyplňte popis');

		$form->addCheckbox('isPublic', 'Určeno pro veřejnost');

		$form->addCheckbox('published', 'Zveřejnit na webu');

		$form->addSubmit('submit', 'Uložit')
			->setAttribute('class', 'btn btn-dark border-radius-none');

		$form->setDefaults(
			$this->item && !$this->item->isNew()
				? [
					'title' => $this->item->getTitle(),
					'place' => $this->item->getPlace(),
					'type' => $this->item->getType(),
					'dateFrom' => $this->item->getDateFrom(),
					'dateTo' => $this->item->getDateTo(),
					'description' => $this->item->getDescription(),
					'isPublic' => $this->item->isPublic(),
					'published' => $this->item->isPublished(),
				]
				: [
					'isPublic' => true,
				]
		);
	}

	public function processForm(array $values): void
	{
		if ($this->item && $this->item->isNew()) {
			$this->em->persist($this->item);
		}

		$this->item->setTitle($values['title']);
		$this->item->setSlug($values['title']);
		$this->item->setPlace($values['place']);
		$this->item->setType($values['type']);
		$this->item->setDateFrom(\DateTimeImmutable::createFromInterface($values['dateFrom']));
		$this->item->setDateTo($values['dateTo'] ? \DateTimeImmutable::createFromInterface($values['dateTo']) : null);
		$this->item->setDescription($values['description']);
		$this->item->setIsPublic($values['isPublic']);
		$this->item->setPublished($values['published']);

		$this->em->flush($this->item);

		$this->presenter->flashMessageSuccess('Úspěšně uloženo');
		$this->presenter->redirect('default');
	}

	public function setItem(?Event $item): EventForm
	{
		$this->item = $item;
		return $this;
	}

}
