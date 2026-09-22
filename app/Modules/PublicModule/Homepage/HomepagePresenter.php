<?php

namespace App\Modules\PublicModule\Homepage;

use App\Model\Entity\Enum\Page;
use App\Model\Query\EventQueryFactory;
use App\Model\Query\GalleryQueryFactory;
use App\Model\Query\NewsQueryFactory;
use App\Modules\PublicModule\BasePresenter;
use Nette\DI\Attributes\Inject;

class HomepagePresenter extends BasePresenter
{
	const UPCOMING_EVENT_COUNT = 3;
	const LATEST_NEWS_COUNT = 3;

	#[Inject]
	public EventQueryFactory $eventQueryFactory;

	#[Inject]
	public NewsQueryFactory $newsQueryFactory;

	#[Inject]
	public GalleryQueryFactory $galleryQueryFactory;

	public function renderSitemap(): void
	{
		$this->template->events = $this->eventQueryFactory->create()->byIsPublished()->fetch();
		$this->template->newsList = $this->newsQueryFactory->create()->byIsPublished()->fetch();
		$this->template->galleries = $this->galleryQueryFactory->create()->byIsPublished()->fetch();
	}

	public function actionDefault(): void
	{
		$this->loadPage(Page::PAGE_HOME_INTERNAL_NAME);
	}

	public function renderDefault(): void
	{
		$this->template->page = $this->page;
		$this->template->pageTitle = null; // na úvodu jen název webu

		$this->template->upcomingEvents = $this->eventQueryFactory
			->create()
			->byIsPublished()
			->byUpcoming()
			->applyPaging(self::UPCOMING_EVENT_COUNT)
			->fetch();

		$this->template->latestNews = $this->newsQueryFactory
			->create()
			->byIsPublished()
			->applyPaging(self::LATEST_NEWS_COUNT)
			->fetch();

		$this->template->latestGallery = $this->galleryQueryFactory
			->create()
			->byIsPublished()
			->applyPaging(1)
			->fetch();
	}

}
