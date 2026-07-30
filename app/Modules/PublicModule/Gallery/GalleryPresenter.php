<?php

namespace App\Modules\PublicModule\Gallery;

use App\Model\Query\GalleryQueryFactory;
use App\Modules\PublicModule\BasePresenter;
use Doctrine\ORM\NoResultException;
use Nette\DI\Attributes\Inject;

class GalleryPresenter extends BasePresenter
{

	#[Inject]
	public GalleryQueryFactory $galleryQueryFactory;

	public function renderDefault(): void
	{
		$this->template->galleries = $this->galleryQueryFactory
			->create()
			->byIsPublished()
			->fetch();
	}

	public function renderDetail(string $slug): void
	{
		try {
			$this->template->gallery = $this->galleryQueryFactory
				->create()
				->byIsPublished()
				->bySlug($slug)
				->fetchOne();
		} catch (NoResultException) {
			$this->error();
		}
	}

}
