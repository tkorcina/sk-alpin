<?php

namespace App\Modules\PublicModule\News;

use App\Components\Controls\Paginator\IPaginatorControlFactory;
use App\Components\Controls\Paginator\Paginator;
use App\Model\Query\NewsQueryFactory;
use App\Modules\PublicModule\BasePresenter;
use Doctrine\ORM\NoResultException;
use Nette\DI\Attributes\Inject;

class NewsPresenter extends BasePresenter
{
	const PER_PAGE = 6;

	protected int $paginatorPage = 1;

	#[Inject]
	public NewsQueryFactory $newsQueryFactory;

	public function actionDefault($page = 1): void
	{
		if (!$page || $page < 0 || !is_numeric($page)) {
			$page = 1;
		}

		$this->paginatorPage = (int) $page;

		$totalCount = $this->newsQueryFactory
			->create()
			->byIsPublished()
			->count();

		$this->paginator = new \Nette\Utils\Paginator();
		$this->paginator->setItemCount($totalCount);
		$this->paginator->setPage($this->paginatorPage);
		$this->paginator->setItemsPerPage(self::PER_PAGE);

		if ($this->paginatorPage > $this->paginator->getPageCount() && $this->paginator->getItemCount() > $this->paginator->getItemsPerPage()) {
			$this->redirect('default', ['page' => 1]);
		}
	}

	public function renderDefault(): void
	{
		$this->template->pageTitle = 'Aktuality';
		$this->template->paginatorPage = $this->paginatorPage;
		$this->template->newsList = $this->newsQueryFactory
			->create()
			->byIsPublished()
			->applyPaging(self::PER_PAGE, ($this->paginatorPage - 1) * self::PER_PAGE)
			->fetch();
	}

	public function renderDetail(string $slug): void
	{
		try {
			$this->template->news = $this->newsQueryFactory
				->create()
				->byIsPublished()
				->bySlug($slug)
				->fetchOne();
		} catch (NoResultException) {
			$this->error();
		}

		$this->template->pageTitle = $this->template->news->getTitle();
		$this->setMeta($this->template->news->getContent(), $this->template->news->getImage());
	}

	public function createComponentPaginator(IPaginatorControlFactory $factory): Paginator
	{
		return $factory->create($this->paginator);
	}

}
