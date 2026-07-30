<?php

declare(strict_types=1);

namespace App\Components\Grids\Base;

use ADT\DoctrineComponents\QueryObject;
use ADT\QueryObjectDataSource\IQueryObjectDataSourceFactory;
use App\Model\Database\EntityManager;
use App\Model\Entity\BaseEntity;
use App\Model\Entity\Config;
use App\Model\Entity\Page;
use App\Model\Entity\Product;
use App\Model\Query\QueryObjectFactory;
use App\Model\Security\SecurityUser;
use App\Modules\BasePresenter;
use Closure;
use Contributte\Translation\Translator;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kdyby\Autowired\Attributes\Autowire;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Control;
use Nette\Application\UI\InvalidLinkException;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Attributes\Inject;
use Nette\DI\Container;
use ReflectionException;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * @property-read DataGrid $grid
 * @property-read BasePresenter $presenter
 */
abstract class BaseGrid extends Control
{
	use AutowireProperties;
	use AutowireComponentFactories;

	#[Inject]
	public Translator $translator;

	#[Autowire]
	protected IQueryObjectDataSourceFactory $queryObjectDataSource;

	#[Autowire]
	protected SecurityUser $securityUser;

	#[Autowire]
	protected EntityManager $em;

	/** @var callable */
	protected $onDelete;

	protected ?string $linkDetail = null;

	protected static string $templateFile = DataGrid::TEMPLATE_DEFAULT;

	abstract protected function getQueryObjectFactoryClass(): string;
	abstract protected function initGrid(DataGrid $grid): void;

	final protected function createComponentGrid(): DataGrid
	{
		$grid = new DataGrid(static::$templateFile);
		$grid->setTranslator($this->translator);

		$grid->setOuterFilterRendering();

		$queryObject = $this->createQueryObject();
		$this->initDataSource($queryObject);

		$queryObjectDataSource = $this->queryObjectDataSource->create($queryObject);
		if ($this->getDataSourceFilterCallback()) {
			$queryObjectDataSource->setFilterCallback($this->getDataSourceFilterCallback());
		}
		$grid->setDataSource($queryObjectDataSource);

		$this->initGrid($grid);

		if ($grid->isSortable()) {
			$grid->setSortableHandler('sortRows!');
		}

		if ($grid->getTemplateFile() === $grid->getOriginalTemplateFile()) {
			$_reflectionClass = new \ReflectionClass($this);
			$grid->setTemplateFile(dirname($_reflectionClass->getFileName()) . '/' . $_reflectionClass->getShortName() . '.latte');
		}

		return $grid;
	}

	public function getYesNoOptions(): array
	{
		return [
			'0' => 'no',
			'1' => 'yes'
		];
	}

	public function getGrid(): DataGrid
	{
		return $this['grid'];
	}

	protected function createQueryObject(): QueryObject
	{
		/** @var QueryObjectFactory $queryObjectFactory */
		$queryObjectFactory = $this->getDic()->getByType($this->getQueryObjectFactoryClass());
		return $queryObjectFactory->create();
	}

	private function createBaseEntityQueryObject(): QueryObject
	{
		if (method_exists($this, 'getBaseEntityQueryFactoryClass')) {
			$queryObjectFactory = $this->getDic()->getByType($this->getBaseEntityQueryFactoryClass());
			return $queryObjectFactory->create();
		}
		return $this->createQueryObject();
	}

	/**
	 * @throws DataGridException
	 */
	final public function render(): void
	{
		/** @var Template $template */
		$template = $this->grid->getTemplate();

		$this->renderGrid($template);

		if ($this->linkDetail) {
			$this->grid->addAction('detail', '', 'detail!')
				->setIcon('search')
				->setTitle('Detail')
				->setClass('ajax datagrid-detail mx-4');
		}

		if ($this->allowEdit()) {
			$this->grid->addAction('edit', '', 'edit!')
				->setIcon('edit')
				->setTitle('Upravit')
				->setClass('ajax datagrid-edit mx-4');
		}

		if ($this->allowDelete()) {
			$this->grid->addAction('delete', '', 'delete!')
				->setIcon('trash')
				->setClass('datagrid-delete mx-4')
				->setTitle('Smazat')
				->setConfirmation(new StringConfirmation('Opravdu smazat položku?'))
				->setRenderCondition(function (BaseEntity $entity) {
					if ($entity instanceof Page || $entity instanceof Product || $entity instanceof Config) {
						return $entity->isDeletable();
					}
					return true;
				});
		}

		$this->template->setFile(__DIR__ . '/BaseGrid.latte')->render();
	}

	protected function getDataSourceFilterCallback(): ?Closure
	{
		return null;
	}

	/**
	 * @throws AbortException
	 * @throws ReflectionException
	 * @throws NonUniqueResultException
	 * @throws NoResultException
	 */
	final public function handleEdit(int $id): void
	{
		if (str_ends_with($this->allowEdit()->redirect, '!')) {
			$methodName = rtrim('handle' . ucfirst($this->allowEdit()->redirect), '!');

			try {
				$this->getPresenter()->{$methodName}($id);
			} catch (InvalidLinkException|\TypeError) {
				$this->getPresenter()->{$methodName}($this->createBaseEntityQueryObject()->byId($id)->fetchOne());
			}

		} else {
			// because of "Argument $order passed to App\Modules\SystemModule\Orders\OrdersPresenter::actionEdit() must be App\Model\Entity\Order, integer given."
			// method Presenter::argsToParams doesn't respect router
			try {
				$this->presenter->redirect($this->allowEdit()->redirect, $id);
			} catch (InvalidLinkException) {
				$this->presenter->redirect($this->allowEdit()->redirect, $this->createBaseEntityQueryObject()->byId($id)->fetchOne());
			}
		}
	}

	public function setLinkDetail(?string $linkDetail): static
	{
		$this->linkDetail = $linkDetail;
		return $this;
	}

	public function handleDetail(int $id): void
	{
		$this->redirectHandle($this->linkDetail, $id);
	}

	public function redirectHandle(string $link, int $id)
	{
		try {
			$this->presenter->redirect($link, $id);
		} catch (InvalidLinkException) {
			$this->presenter->redirect($link, $this->createBaseEntityQueryObject()->byId($id)->fetchOne());
		}
	}

	/**
	 * @throws BadRequestException
	 * @throws ReflectionException
	 * @throws NonUniqueResultException
	 */
	final public function handleDelete($id): void
	{
		if (!$this->allowDelete()) {
			$this->error();
		}

		if (!$entity = $this->createBaseEntityQueryObject()->byId($id)->fetchOneOrNull()) {
			$this->error();
		}

		if ($this->allowDelete()->onDelete && !($this->allowDelete()->onDelete)($entity)) {
			return;
		}

		if (
			($entity instanceof Page && !$entity->isDeletable())
			||
			($entity instanceof Product && !$entity->isDeletable())
			||
			($entity instanceof Config && !$entity->isDeletable())
		) {
			return;
		}

		$this->em->remove($entity);
		$this->em->flush();

		$this->presenter->flashMessage($this->translator->translate('a.delete.yes'),'success');
		$this->presenter->redirect('this');
	}

	final public function addFilterQuery(DataGrid $grid): void
	{
		$grid->addFilterText('q', '')
			->setTemplate('datagrid_filter_q.latte')
			->setCondition(function ($query, $value) {
				$query->byQuery($value);
			});
	}

	protected function allowEdit(): ?EditParams
	{
		return null;
	}

	protected function allowDelete(): ?DeleteParams
	{
		return null;
	}

	protected function initDataSource($queryObject): void
	{
	}

	protected function renderGrid(Template $template): void
	{
	}

	protected function getDic(): Container
	{
		return $this->autowirePropertiesLocator;
	}

	public function getEntityManager(): EntityManager
	{
		return $this->em;
	}


	public function translateArray(array $array): array
	{
		return array_map(fn(string $value) => $this->translator->translate($value), $array);
	}
}
