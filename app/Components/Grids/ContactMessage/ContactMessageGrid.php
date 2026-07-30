<?php

declare(strict_types=1);

namespace App\Components\Grids\ContactMessage;

use App\Components\Grids\Base\BaseGrid;
use App\Components\Grids\Base\DataGrid;
use App\Components\Grids\Base\DeleteParams;
use App\Model\Entity\ContactMessage;
use App\Model\Query\ContactMessageQueryFactory;
use Nette\Utils\Strings;


class ContactMessageGrid extends BaseGrid
{

	public function initGrid(DataGrid $grid): void
	{
		$grid->setItemsPerPageList([50000]);
		$grid->setDefaultPerPage(50000);
		$grid->setPagination(false);

		$grid->setDefaultSort(['createdAt' => 'DESC']);

		$grid->addColumnDateTime('createdAt', 'Přijato')
			->setFormat('j. n. Y H:i')
			->setSortable();

		$grid->addColumnText('name', 'Jméno')
			->setFilterText()
			->setAttribute('autocomplete', 'off');

		$grid->addColumnText('email', 'E-mail')
			->setRenderer(function (ContactMessage $message) {
				return \Nette\Utils\Html::el('a')
					->href('mailto:' . $message->getEmail())
					->setText($message->getEmail());
			});

		$grid->addColumnText('message', 'Zpráva')
			->setRenderer(fn (ContactMessage $message) => Strings::truncate($message->getMessage(), 120));
	}

	protected function getQueryObjectFactoryClass(): string
	{
		return ContactMessageQueryFactory::class;
	}

	protected function allowDelete(): ?DeleteParams
	{
		return new DeleteParams();
	}

}
