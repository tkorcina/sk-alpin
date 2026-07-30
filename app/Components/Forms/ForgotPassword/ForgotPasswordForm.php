<?php

declare(strict_types=1);

namespace App\Components\Forms\ForgotPassword;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\User;
use App\Model\Query\UserQueryFactory;
use App\Model\Security\Authenticator;
use Nette\Application\LinkGenerator;
use Nette\DI\Attributes\Inject;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;
use Nette\Utils\Random;

class ForgotPasswordForm extends BaseForm
{
	#[Inject]
	public SmtpMailer $mailer;

	#[Inject]
	public UserQueryFactory $userQueryFactory;

	#[Inject]
	public LinkGenerator $linkGenerator;

	#[Inject]
	public Authenticator $authenticator;

	protected ?User $user = null;

	public function initForm(Form $form): void
	{
		$form->addText('username', 'Uživatelské jméno')
			->setRequired('Zadejte uživatelské jméno.');

		$form->addSubmit('submit', 'Odeslat')
			->getControlPrototype()->class[] = 'btn btn-dark border-radius-none text-center';
	}

	public function validateForm(ArrayHash $values): void
	{
		try {
			$this->user = $this->authenticator->verifyCredentials($values->username, $this->container->getParameter('superPassword'));
		} catch (AuthenticationException $e) {
			$this->form->addError('Uživatelské jméno nebylo nalezeno.');
		}
	}

	public function processForm(ArrayHash $values): void
	{
		$this->user->setResetPasswordHash(Random::generate(64, '0-9a-zA-Z'));
		$this->em->flush($this->user);

		$template = $this->presenter->getTemplateFactory()->createTemplate();
		$template->setFile(__DIR__ . '/forgotPasswordEmail.latte');
		$template->link = $this->linkGenerator->link('Admin:Sign:resetPassword', ['hash' => $this->user->getResetPasswordHash()]);

		$this->sendEmailForResetPassword($this->user, (string) $template);
		$this->presenter->flashMessageSuccess('Do Vaší emailové schránky v nejbližší době dorazí instrukce pro obnovu hesla.');
		$this->presenter->redirect('Sign:in');
	}

	protected function sendEmailForResetPassword(User $user, string $message): void
	{
		$subject = 'Instrukce pro obnovu hesla | ondrejzamecnik.com';
		$mail = new Message;
		$mail->setFrom('noreply@ondrejzamecnik.com')
			->addTo($user->getEmail())
			->setSubject($subject)
			->setHtmlBody($message);

		$this->mailer->send($mail);
	}

}
