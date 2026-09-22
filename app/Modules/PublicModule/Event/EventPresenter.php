<?php

namespace App\Modules\PublicModule\Event;

use App\Model\Query\EventQueryFactory;
use App\Model\Query\GalleryQueryFactory;
use App\Modules\PublicModule\BasePresenter;
use Doctrine\ORM\NoResultException;
use Nette\DI\Attributes\Inject;

class EventPresenter extends BasePresenter
{

	#[Inject]
	public EventQueryFactory $eventQueryFactory;

	#[Inject]
	public GalleryQueryFactory $galleryQueryFactory;

	public function renderDefault(): void
	{
		$this->template->pageTitle = 'Akce';
		$this->template->upcomingEvents = $this->eventQueryFactory
			->create()
			->byIsPublished()
			->byUpcoming()
			->fetch();

		$this->template->pastEvents = $this->eventQueryFactory
			->create()
			->byIsPublished()
			->byPast()
			->orderBy('dateFrom', 'DESC')
			->fetch();
	}

	public function renderDetail(string $slug): void
	{
		try {
			$event = $this->eventQueryFactory
				->create()
				->byIsPublished()
				->bySlug($slug)
				->fetchOne();
		} catch (NoResultException) {
			$this->error();
		}

		$this->template->event = $event;
		$this->template->pageTitle = $event->getTitle();
		$this->template->galleries = $this->galleryQueryFactory
			->create()
			->byIsPublished()
			->byEvent($event)
			->fetch();
	}

}
