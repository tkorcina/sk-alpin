<?php

declare(strict_types=1);

namespace App\Components\Grids\Page;

use App\Components\Grids\Base\BaseGrid;
use App\Components\Grids\Base\DataGrid;
use App\Components\Grids\Base\EditParams;
use App\Model\Entity\Page;
use App\Model\Query\PageQueryFactory;


class PageGrid extends BaseGrid
{

	public function initGrid(DataGrid $grid): void
	{
		$grid->setItemsPerPageList([50000]);
		$grid->setDefaultPerPage(50000);
		$grid->setPagination(false);

		$grid->addColumnText('title', 'Název');

		$grid->addColumnText('internalName', 'Umístění')
			->setRenderer(fn (Page $page) => match ($page->getInternalName()) {
				\App\Model\Entity\Enum\Page::PAGE_HOME_INTERNAL_NAME => 'Úvodní stránka',
				\App\Model\Entity\Enum\Page::PAGE_ABOUT_INTERNAL_NAME => 'O nás',
				\App\Model\Entity\Enum\Page::PAGE_CONTACT_INTERNAL_NAME => 'Kontakt',
				default => $page->getInternalName(),
			});
	}

	protected function getQueryObjectFactoryClass(): string
	{
		return PageQueryFactory::class;
	}

	protected function allowEdit(): ?EditParams
	{
		return new EditParams('edit');
	}

}
