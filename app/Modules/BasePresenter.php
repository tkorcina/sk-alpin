<?php

namespace App\Modules;

use App\Model\Database\EntityManager;
use App\Model\Security\SecurityUser;
use App\Model\Service\ConfigService;
use App\Model\Translator;
use Kdyby\Autowired\Attributes\Autowire;
use Nette\Application\Attributes\Persistent;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\DI\Attributes\Inject;
use Nette\Mail\SmtpMailer;

/**
 * @method SecurityUser getUser()
 * @property SecurityUser $user
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{

	use \Kdyby\Autowired\AutowireProperties;
	use \Kdyby\Autowired\AutowireComponentFactories;

	#[Autowire]
	protected TemplateFactory $templateFactory;

	#[Autowire]
	protected EntityManager $em;

	#[Autowire]
	public Translator $translator;

	#[Autowire]
	public SmtpMailer $mailer;

	#[Inject]
	public ConfigService $configService;

	#[Persistent]
	public $locale = 'cs';

	public function flashMessageError(string $message): \stdClass {
		return $this->flashMessageCommon($message, 'danger');
	}

	public function flashMessageWarning(string $message): \stdClass {
		return $this->flashMessageCommon($message, 'warning');
	}

	public function flashMessageSuccess(string $message): \stdClass {
		return $this->flashMessageCommon($message, 'success');
	}

	public function flashMessageInfo(string $message): \stdClass {
		return $this->flashMessageCommon($message, 'info');
	}

	/** @internal */
	private function flashMessageCommon(string $message, string $type)
	{
		$this->redrawControl('flashes');
		return parent::flashMessage($message, $type);
	}

	public function beforeRender(): void
	{
		$this->getTemplate()->translator = $this->translator;
		parent::beforeRender();
	}

	public function isLogged(): bool
	{
		return $this->getUser()->isLoggedIn();
	}

	public function _(): string
	{
		return call_user_func_array([$this->translator, 'translate'], func_get_args());
	}

	public function startup(): void
	{
		$locale = $this->getParameter('locale');
		$this->locale = in_array($locale, ['cs', 'en'], true) ? $locale : 'cs';
		$this->translator->setLocale($this->locale);
		parent::startup();
	}
}

