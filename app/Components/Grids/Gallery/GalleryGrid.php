<?php

declare(strict_types=1);

namespace App\Components\Grids\Gallery;

use App\Components\Grids\Base\BaseGrid;
use App\Components\Grids\Base\DataGrid;
use App\Components\Grids\Base\DeleteParams;
use App\Components\Grids\Base\EditParams;
use App\Model\Entity\Gallery;
use App\Model\Query\GalleryQueryFactory;


class GalleryGrid extends BaseGrid
{

	public function initGrid(DataGrid $grid): void
	{
		$grid->setItemsPerPageList([50000]);
		$grid->setDefaultPerPage(50000);
		$grid->setPagination(false);

		$grid->addColumnText('title', 'Název')
			->setFilterText()
			->setAttribute('autocomplete', 'off');

		$grid->addColumnDateTime('date', 'Datum')
			->setFormat('j. n. Y')
			->setSortable();

		$grid->addColumnText('event', 'Akce')
			->setRenderer(fn (Gallery $gallery) => $gallery->getEvent()?->getTitle() ?? '—');

		$grid->addColumnText('photos', 'Počet fotek')
			->setRenderer(fn (Gallery $gallery) => count($gallery->getPhotos()));

		$grid->addColumnText('published', 'Zveřejněno')
			->setRenderer(fn (Gallery $gallery) => $gallery->isPublished() ? 'Ano' : 'Ne');
	}

	protected function getQueryObjectFactoryClass(): string
	{
		return GalleryQueryFactory::class;
	}

	protected function allowEdit(): ?EditParams
	{
		return new EditParams('edit');
	}

	protected function allowDelete(): ?DeleteParams
	{
		return new DeleteParams();
	}

}
