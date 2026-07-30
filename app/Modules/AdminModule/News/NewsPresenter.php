<?php

namespace App\Modules\AdminModule\News;

use App\Components\Forms\News\NewsForm;
use App\Components\Forms\News\NewsFormFactory;
use App\Components\Grids\News\NewsGrid;
use App\Components\Grids\News\NewsGridFactory;
use App\Model\Entity\News;
use App\Model\Query\NewsQueryFactory;
use App\Modules\AdminModule\BasePresenter;
use Doctrine\ORM\NonUniqueResultException;
use Nette\DI\Attributes\Inject;

class NewsPresenter extends BasePresenter
{
	public ?News $row = null;

	#[Inject]
	public NewsQueryFactory $queryFactory;

	public function actionDefault(): void
	{}

	public function createComponentNewsGrid(NewsGridFactory $gridFactory): NewsGrid
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

	public function createComponentNewsForm(NewsFormFactory $factory): NewsForm
	{
		return $factory->create()
			->setItem($this->row ?: new News(new \DateTimeImmutable()));
	}

	public function handleDeleteImage($news): void
	{
		if (!$news) {
			$this->error();
		}

		try {
			$row = $this->queryFactory->create()->byId($news)->fetchOneOrNull();
		} catch (NonUniqueResultException) {
			$row = null;
		}

		if (!$row) {
			$this->error();
		}

		if ($row->getImage()) {
			$row->getImage()->setActive(false);
			$row->setImage(null);
			$this->em->flush();
			$this->flashMessageSuccess('Obrázek úspěšně smazán');
		}

		$this->redirect('edit', ['id' => $row->getId()]);
	}

}
