<?php

namespace App\Modules\AdminModule\ContactMessage;

use App\Components\Grids\ContactMessage\ContactMessageGrid;
use App\Components\Grids\ContactMessage\ContactMessageGridFactory;
use App\Modules\AdminModule\BasePresenter;

class ContactMessagePresenter extends BasePresenter
{

	public function actionDefault(): void
	{}

	public function createComponentContactMessageGrid(ContactMessageGridFactory $gridFactory): ContactMessageGrid
	{
		return $gridFactory->create();
	}

}
