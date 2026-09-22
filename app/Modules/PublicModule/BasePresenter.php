<?php

namespace App\Modules\PublicModule;

use App\Model\Entity\File;
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
		$settings = $this->configService->getSiteSettings();
		$this->template->settings = $settings;

		if ($this->page && !isset($this->template->pageTitle)) {
			$this->template->pageTitle = $this->page->getTitle();
		}

		// SEO: meta description a OG obrázek — presenter může nastavit vlastní přes setMeta(), jinak výchozí z Nastavení
		if (!isset($this->template->metaDescription)) {
			$this->template->metaDescription = $settings->getDescription();
		}
		if (!isset($this->template->ogImage)) {
			$this->template->ogImage = $this->absoluteFileUrl($settings->getHeroImage());
		}
		$this->template->canonicalUrl = $this->link('//this');

		parent::beforeRender();
	}

	/**
	 * Meta description (z HTML obsahu udělá čistý text zkrácený na ~160 znaků) a OG obrázek pro detailové stránky.
	 */
	protected function setMeta(?string $description, ?File $image = null): void
	{
		if ($description !== null) {
			$text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($description), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
			if ($text !== '') {
				$this->template->metaDescription = \Nette\Utils\Strings::truncate($text, 160);
			}
		}

		if ($image) {
			$this->template->ogImage = $this->absoluteFileUrl($image);
		}
	}

	protected function absoluteFileUrl(?File $file): ?string
	{
		if (!$file) {
			return null;
		}

		return rtrim($this->getHttpRequest()->getUrl()->getBaseUrl(), '/') . '/' . ltrim($file->getPath(), '/');
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
