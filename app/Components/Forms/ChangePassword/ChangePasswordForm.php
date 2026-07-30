<?php

declare(strict_types=1);

namespace App\Components\Forms\ChangePassword;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\User;
use App\Model\Security\Authenticator;
use App\Model\Security\SecurityUser;
use Nette\DI\Attributes\Inject;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;

class ChangePasswordForm extends BaseForm
{

	#[Inject]
	public Authenticator $authenticator;

	#[Inject]
	public SecurityUser $securityUser;

	protected ?User $user = null;

	public function initForm(Form $form): void
	{
		if ($this->securityUser->isLoggedIn()) {
			$form->addPassword('oldPassword', 'Aktuální heslo')
				->setRequired("Zadejte aktuální heslo");
		}

		$form->addPassword("password", 'Nové heslo')
			->setRequired("Zadejte nové heslo");

		$form['password']->addRule(Form::MIN_LENGTH, 'Heslo musí obsahovat minimálně 10 znaků.', 10);

		$form->addPassword("password_check", 'Nové heslo pro kontrolu')
			->setRequired("Zadejte nové heslo pro kontrolu");

		$form['password_check']->addRule($form::EQUAL, "Hesla se neshodují", $form["password"]);

		$form->addSubmit('submit', 'Změnit heslo')
			->getControlPrototype()->class[] = 'btn btn-dark border-radius-none text-center';
	}

	public function validateForm(ArrayHash $values): void
	{

		if ($this->securityUser->isLoggedIn()) {

			if (!$this->authenticator->verifyPassword($values->oldPassword, $this->securityUser->getIdentity()->getPassword())) {
				$this->form->addError('Neplatné aktuální heslo.');
			}

		}

		if (
			$this->authenticator->verifyPassword(
				$values->password,
				(string) (
					$this->user
						? $this->user->getPassword()
						: $this->securityUser->getIdentity()->getPassword()
				)
			)
		) {
			$this->form->addError('Nové heslo musí být odlišné od původního.');
		}
	}

	public function processForm(ArrayHash $values): void
	{
		$user = $this->user ?: $this->securityUser->getIdentity();
		$hash = (new Passwords())->hash($values->password);
		$user->setPassword($hash);
		$this->em->flush($user);
		$this->presenter->flashMessageSuccess('Heslo bylo úspěšně změněno.');
		$this->presenter->redirect('Homepage:default');
	}

	public function setUser(?User $user): ChangePasswordForm
	{
		$this->user = $user;
		return $this;
	}


}
