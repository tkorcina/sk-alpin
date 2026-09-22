<?php

namespace App\Modules\PublicModule\Page;

use App\Modules\PublicModule\BasePresenter;

class PagePresenter extends BasePresenter
{

	public function actionDefault(string $internalName): void
	{
		$this->loadPage($internalName);
	}

	public function renderDefault(): void
	{
		$this->template->page = $this->page;
		$this->setMeta($this->page->getContent());
	}

}
