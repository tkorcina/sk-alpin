<?php

namespace App\Modules\AdminModule\Event;

use App\Components\Forms\Event\EventForm;
use App\Components\Forms\Event\EventFormFactory;
use App\Components\Grids\Event\EventGrid;
use App\Components\Grids\Event\EventGridFactory;
use App\Model\Entity\Event;
use App\Model\Query\EventQueryFactory;
use App\Modules\AdminModule\BasePresenter;
use Doctrine\ORM\NonUniqueResultException;
use Nette\DI\Attributes\Inject;

class EventPresenter extends BasePresenter
{
	public ?Event $row = null;

	#[Inject]
	public EventQueryFactory $queryFactory;

	public function actionDefault(): void
	{}

	public function createComponentEventGrid(EventGridFactory $gridFactory): EventGrid
	{
		return $gridFactory->create();
	}

	public function actionEdit($id = null): void
	{
		try {
			$this->row = $id
				? $this->queryFactory->create()->byId($id)->fetchOneOrNull()
				: null;
		} catch (NonUniqueResultException) {
		} catch (\ReflectionException) {
			$this->error();
		}

		if ($id && !$this->row) {
			$this->error();
		}
	}

	public function renderEdit(): void
	{
		$this->template->row = $this->row;
	}

	public function createComponentEventForm(EventFormFactory $factory): EventForm
	{
		return $factory->create()
			->setItem($this->row ?: new Event(new \DateTimeImmutable()));
	}

}
