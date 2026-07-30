<?php

namespace App\Modules\PublicModule;

use Nette\Application\Attributes\Persistent;
use Nette\Utils\Paginator;

abstract class BasePresenter extends \App\Modules\BasePresenter
{
	protected ?Paginator $paginator = null;

	#[Persistent]
	public $locale = 'cs';

	public function startup(): void
	{
		$locale = $this->getParameter('locale');
		$this->locale = in_array($locale, ['cs', 'en'], true) ? $locale : 'cs';
		$this->translator->setLocale($this->locale);
		parent::startup();
	}

	public function beforeRender(): void
	{
		$this->getTemplate()->translator = $this->translator;
		$this->template->locale = $this->translator->getLocale() ?? 'cs';
		$this->template->validateCache = !$this->getHttpRequest()->getCookie('cacheDeleted');
		parent::beforeRender();
	}

}
