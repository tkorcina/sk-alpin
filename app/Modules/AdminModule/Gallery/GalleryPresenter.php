<?php

namespace App\Modules\AdminModule\Gallery;

use App\Components\Forms\Gallery\GalleryForm;
use App\Components\Forms\Gallery\GalleryFormFactory;
use App\Components\Grids\Gallery\GalleryGrid;
use App\Components\Grids\Gallery\GalleryGridFactory;
use App\Model\Entity\Gallery;
use App\Model\Query\GalleryQueryFactory;
use App\Modules\AdminModule\BasePresenter;
use Doctrine\ORM\NonUniqueResultException;
use Nette\DI\Attributes\Inject;

class GalleryPresenter extends BasePresenter
{
	public ?Gallery $row = null;

	#[Inject]
	public GalleryQueryFactory $queryFactory;

	public function actionDefault(): void
	{}

	public function createComponentGalleryGrid(GalleryGridFactory $gridFactory): GalleryGrid
	{
		return $gridFactory->create();
	}

	public function actionEdit($id = null): void
	{
		$this->row = $id ? $this->getGallery($id) : null;
	}

	public function renderEdit(): void
	{
		$this->template->row = $this->row;
	}

	public function createComponentGalleryForm(GalleryFormFactory $factory): GalleryForm
	{
		return $factory->create()
			->setGallery($this->row ?: new Gallery());
	}

	public function handleDeletePhoto($gallery, $photoId): void
	{
		if (!$gallery || !$photoId) {
			$this->error();
		}

		$row = $this->getGallery($gallery);

		foreach ($row->getPhotos() as $photo) {
			if ($photo->getId() === (int) $photoId) {
				$photo->getFile()->setActive(false);
				$row->removePhoto($photo);
				$this->em->flush();
				$this->flashMessageSuccess('Fotografie úspěšně smazána');
				break;
			}
		}

		$this->redirect('edit', ['id' => $row->getId()]);
	}

	protected function getGallery($id): Gallery
	{
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
