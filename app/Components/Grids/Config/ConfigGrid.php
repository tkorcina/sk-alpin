<?php

declare(strict_types=1);

namespace App\Components\Grids\Config;

use App\Components\Grids\Base\BaseGrid;
use App\Components\Grids\Base\DataGrid;
use App\Components\Grids\Base\DeleteParams;
use App\Components\Grids\Base\EditParams;
use App\Model\Entity\Config;
use App\Model\Query\ConfigQueryFactory;


class ConfigGrid extends BaseGrid
{

	public function initGrid(DataGrid $grid): void
	{
		$grid->setItemsPerPageList([50000]);
		$grid->setDefaultPerPage(50000);
		$grid->setPagination(false);

		$grid->addColumnText('key', 'Identifikátor');
		$grid->addColumnText('name', 'Název');
		$grid->addColumnText('value', 'Hodnota');
	}

	protected function getQueryObjectFactoryClass(): string
	{
		return ConfigQueryFactory::class;
	}

	protected function allowEdit(): ?EditParams
	{
		return new EditParams('editConfig');
	}

	protected function allowDelete(): ?DeleteParams
	{
		return new DeleteParams(function (Config $item) {
			return false;
		});
	}

}
