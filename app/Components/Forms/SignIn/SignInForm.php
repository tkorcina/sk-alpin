<?php

declare(strict_types=1);

namespace App\Components\Forms\SignIn;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\User;
use App\Model\Security\Authenticator;
use Nette\DI\Attributes\Inject;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

class SignInForm extends BaseForm
{

	#[Inject]
	public Authenticator $authenticator;

	protected ?User $user = null;

	public function initForm(Form $form): void
	{
		$form->getElementPrototype()->class[] = 'login-form';

		$form->addText('email', 'Uživatelské jméno')
			->setRequired('Zadejte uživatelské jméno');

		$form->addPassword('password', 'Heslo')
			->setRequired('Zadejte heslo');

		$form->addSubmit('submit', 'Přihlásit')
			->getControlPrototype()->class[] = 'btn btn-dark border-radius-none text-center';
	}

	public function validateForm(ArrayHash $values): void
	{
		try {
			$this->user = $this->authenticator->verifyCredentials($values->email, $values->password);
		} catch (AuthenticationException $e) {
			$this->form->addError($e->getMessage());
		}
	}

	public function processForm(ArrayHash $values): void
	{
		$this->presenter->user->login($this->user);
		$this->presenter->redirect('Homepage:default');
	}
}
