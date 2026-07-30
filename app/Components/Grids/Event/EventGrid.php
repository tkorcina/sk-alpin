<?php

declare(strict_types=1);

namespace App\Components\Grids\Event;

use App\Components\Grids\Base\BaseGrid;
use App\Components\Grids\Base\DataGrid;
use App\Components\Grids\Base\DeleteParams;
use App\Components\Grids\Base\EditParams;
use App\Model\Entity\Event;
use App\Model\Query\EventQueryFactory;


class EventGrid extends BaseGrid
{

	public function initGrid(DataGrid $grid): void
	{
		$grid->setItemsPerPageList([50000]);
		$grid->setDefaultPerPage(50000);
		$grid->setPagination(false);

		$grid->setDefaultSort(['dateFrom' => 'DESC']);

		$grid->addColumnText('title', 'Název')
			->setFilterText()
			->setAttribute('autocomplete', 'off');

		$grid->addColumnDateTime('dateFrom', 'Od')
			->setFormat('j. n. Y')
			->setSortable();

		$grid->addColumnDateTime('dateTo', 'Do')
			->setFormat('j. n. Y');

		$grid->addColumnText('place', 'Místo');

		$grid->addColumnText('type', 'Typ')
			->setRenderer(fn (Event $event) => $event->getTypeLabel());

		$grid->addColumnText('isPublic', 'Pro veřejnost')
			->setRenderer(fn (Event $event) => $event->isPublic() ? 'Ano' : 'Ne');

		$grid->addColumnText('published', 'Zveřejněno')
			->setRenderer(fn (Event $event) => $event->isPublished() ? 'Ano' : 'Ne');
	}

	protected function getQueryObjectFactoryClass(): string
	{
		return EventQueryFactory::class;
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
