<?php

namespace App\Modules\AdminModule\Sign;

use App\Components\Forms\ChangePassword\ChangePasswordForm;
use App\Components\Forms\ChangePassword\ChangePasswordFormFactory;
use App\Components\Forms\ForgotPassword\ForgotPasswordForm;
use App\Components\Forms\ForgotPassword\ForgotPasswordFormFactory;
use App\Components\Forms\SignIn\SignInForm;
use App\Components\Forms\SignIn\SignInFormFactory;
use App\Model\Entity\User;
use App\Model\Query\UserQueryFactory;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Nette\DI\Attributes\Inject;

class SignPresenter extends \App\Modules\BasePresenter
{
	#[Inject]
	public UserQueryFactory $userQueryFactory;

	protected ?User $userEntity = null;

	public function startup(): void
	{
		parent::startup();

		if ($this->isLogged() && $this->getAction() !== 'out') {
			$this->redirect('Homepage:default');
		}
	}

	public function actionIn(?string $errorMsg): void
	{
		if ($errorMsg) {
			$this->flashMessageError($errorMsg);
		}

		if ($this->getParameter('fraudDetected')) {
			$this->flashMessageError('Bylo detekováno podezřelé chování.');
		}
	}

	public function actionOut(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->getUser()->logout(true);
		}

		$this->redirect('in');
	}

	public function createComponentLoginForm(SignInFormFactory $factory): SignInForm
	{
		return $factory->create();
	}

	public function actionForgotPassword(): void
	{}

	public function createComponentChangePasswordForm(ChangePasswordFormFactory $factory): ChangePasswordForm
	{
		return $factory->create()->setUser($this->userEntity);
	}

	public function actionResetPassword(?string $hash = null): void
	{
		if (!$hash || $this->getParameter('fraudDetected')) {
			$this->error();
		}

		try {
			$this->userEntity = $this->userQueryFactory
				->create()
				->by('resetPasswordHash', $hash)
				->fetchOne();
		} catch (\ReflectionException | NonUniqueResultException |NoResultException $e) {
			$this->error();
		}
	}

	public function createComponentForgotPasswordForm(ForgotPasswordFormFactory $factory): ForgotPasswordForm
	{
		return $factory->create();
	}


}