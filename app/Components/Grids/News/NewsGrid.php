<?php

declare(strict_types=1);

namespace App\Components\Grids\News;

use App\Components\Grids\Base\BaseGrid;
use App\Components\Grids\Base\DataGrid;
use App\Components\Grids\Base\DeleteParams;
use App\Components\Grids\Base\EditParams;
use App\Model\Entity\News;
use App\Model\Query\NewsQueryFactory;


class NewsGrid extends BaseGrid
{

	public function initGrid(DataGrid $grid): void
	{
		$grid->setItemsPerPageList([50000]);
		$grid->setDefaultPerPage(50000);
		$grid->setPagination(false);

		$grid->setDefaultSort(['publishedAt' => 'DESC']);

		$grid->addColumnText('title', 'Titulek')
			->setFilterText()
			->setAttribute('autocomplete', 'off');

		$grid->addColumnDateTime('publishedAt', 'Datum')
			->setFormat('j. n. Y')
			->setSortable();

		$grid->addColumnText('image', 'Obrázek')
			->setRenderer(fn (News $news) => $news->getImage() ? 'Ano' : '—');

		$grid->addColumnText('published', 'Zveřejněno')
			->setRenderer(fn (News $news) => $news->isPublished() ? 'Ano' : 'Ne');
	}

	protected function getQueryObjectFactoryClass(): string
	{
		return NewsQueryFactory::class;
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
