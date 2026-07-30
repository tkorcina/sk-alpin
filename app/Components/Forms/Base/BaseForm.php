<?php

namespace App\Components\Forms\Base;

use App\Model\Database\EntityManager;
use App\Model\Security\SecurityUser;
use App\Model\Service\FileService;
use Contributte\Translation\Translator;
use Kdyby\Autowired\Attributes\Autowire;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nette\DI\Container;

abstract class BaseForm extends \ADT\DoctrineForms\BaseForm
{
	use AutowireProperties;
	use AutowireComponentFactories;

	#[Autowire]
	protected EntityManager $em;

	#[Autowire]
	protected Translator $translator;

	#[Autowire]
	protected Container $container;

	#[Autowire]
	protected SecurityUser $securityUser;

	protected string $wwwDir = WWW_DIR;

	public function __construct()
	{
		$this->setOnBeforeInitForm(function($form) {
			$this->form->setEntityManager($this->em);
		});
		parent::__construct();
	}

	protected function createComponentForm(): EntityForm
	{
		$form = new EntityForm();
		$form->setTranslator($this->translator);
		$form->setRenderer((new FormRenderer($form)));
		return $form;
	}

	public function setWwwDir(string $wwwDir): static
	{
		$this->wwwDir = $wwwDir;
		return $this;
	}


}