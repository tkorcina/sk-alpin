<?php

namespace App\Modules\PublicModule\Homepage;

use App\Model\Entity\Enum\ConfigType;
use App\Modules\PublicModule\BasePresenter;

class HomepagePresenter extends BasePresenter
{
	public function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->validateCache = !$this->getHttpRequest()->getCookie('cacheDeleted');
	}

	public function renderDefault(): void
	{

    }

}
