<?php

declare(strict_types=1);

namespace App\Components\Forms\User;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\User;
use Nette\Utils\ArrayHash;

class UserForm extends BaseForm
{
	protected User $user;

	public function initForm(Form $form): void
	{
		$form->addText('firstName', 'Jméno')
			->setRequired('Toto pole je povinné.');

		$form->addText('lastName', 'Příjmení')
			->setRequired('Toto pole je povinné.');

		$form->addText('email', 'Email')
			->setOption('description', 'Uživatelské jméno')
			->setRequired('Toto pole je povinné.');

		$form->addPhoneNumber('phone', 'Telefonní číslo', 'Zadejte platné telefonní číslo.')
			->setHtmlAttribute('class', '');


		$form->setDefaults([
			'id' => $this->user->getId(),
			'firstName' => $this->user->getFirstName(),
			'lastName' => $this->user->getLastName(),
			'email' => $this->user->getEmail(),
			'phone' => $this->user->getPhoneNumber(),
		]);

		$form->addSubmit('submit', 'Uložit')
			->getControlPrototype()->class[] = 'btn btn-dark border-radius-none text-center';
	}

	public function processForm(ArrayHash $values): void
	{
		$this->user->setFirstName($values->firstName);
		$this->user->setLastName($values->lastName);
		$this->user->setEmail($values->email);
		$this->user->setPhoneNumber((string) $values->phone);
		$this->em->flush();

		$this->presenter->flashMessage('Úspěšně uloženo.');
		$this->presenter->redirect('Homepage:default');
	}

	public function setUser(User $user): UserForm
	{
		$this->user = $user;
		return $this;
	}
}
