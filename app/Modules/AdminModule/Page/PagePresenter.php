<?php

namespace App\Modules\AdminModule\Page;

use App\Components\Forms\Page\PageForm;
use App\Components\Forms\Page\PageFormFactory;
use App\Components\Grids\Page\PageGrid;
use App\Components\Grids\Page\PageGridFactory;
use App\Model\Entity\Page;
use App\Model\Query\PageQueryFactory;
use App\Modules\AdminModule\BasePresenter;
use Doctrine\ORM\NonUniqueResultException;
use Nette\DI\Attributes\Inject;

class PagePresenter extends BasePresenter
{
	public ?Page $row = null;

	#[Inject]
	public PageQueryFactory $queryFactory;

	public function actionDefault(): void
	{}

	public function createComponentPageGrid(PageGridFactory $gridFactory): PageGrid
	{
		return $gridFactory->create();
	}

	public function actionEdit($id = null): void
	{
		$this->row = $this->getPage($id);
	}

	public function renderEdit(): void
	{
		$this->template->row = $this->row;
	}

	public function createComponentPageForm(PageFormFactory $factory): PageForm
	{
		return $factory->create()
			->setItem($this->row);
	}

	public function handleDeleteImage($page): void
	{
		$row = $this->getPage($page);

		if ($row->getImage()) {
			$row->getImage()->setActive(false);
			$row->setImage(null);
			$this->em->flush();
			$this->flashMessageSuccess('Obrázek úspěšně smazán');
		}

		$this->redirect('edit', ['id' => $row->getId()]);
	}

	protected function getPage($id): Page
	{
		if (!$id) {
			$this->error();
		}

		try {
			$row = $this->queryFactory->create()->byId($id)->fetchOneOrNull();
		} catch (NonUniqueResultException) {
			$row = null;
		}

		if (!$row) {
			$this->error();
		}

		return $row;
	}

}
