<?php

namespace App\Modules\PublicModule;

use App\Model\Entity\Page;
use App\Model\Query\PageQueryFactory;
use Doctrine\ORM\NoResultException;
use Nette\Application\Attributes\Persistent;
use Nette\DI\Attributes\Inject;
use Nette\Utils\Paginator;

abstract class BasePresenter extends \App\Modules\BasePresenter
{
	protected ?Paginator $paginator = null;

	protected ?Page $page = null;

	#[Inject]
	public PageQueryFactory $pageQueryFactory;

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

	protected function loadPage(string $internalName): Page
	{
		try {
			$this->page = $this->pageQueryFactory
				->create()
				->byInternalName($internalName)
				->fetchOne();
		} catch (NoResultException) {
			$this->error();
		}

		return $this->page;
	}

}
