<?php

declare(strict_types=1);

namespace App\Components\Forms\Contact;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\ContactMessage;
use Kdyby\Autowired\Attributes\Autowire;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Nette\Utils\ArrayHash;

class ContactForm extends BaseForm
{

	#[Autowire]
	protected SmtpMailer $mailer;

	protected string $contactEmail;

	protected string $noReplyEmail;

	public function setEmails(string $contactEmail, string $noReplyEmail): static
	{
		$this->contactEmail = $contactEmail;
		$this->noReplyEmail = $noReplyEmail;
		return $this;
	}

	public function initForm(Form $form): void
	{
		$form->addText('name', 'Jméno')
			->setRequired('Zadejte své jméno.');

		$form->addText('email', 'E-mail')
			->addRule(Form::EMAIL, 'Zadejte platný e-mail.')
			->setRequired('Zadejte svůj e-mail.');

		$form->addTextArea('message', 'Zpráva')
			->setRequired('Napište zprávu.');

		// antispam honeypot - skryté pole, které vyplní jen robot
		$form->addText('website')
			->setHtmlAttribute('style', 'display: none')
			->setHtmlAttribute('tabindex', '-1')
			->setHtmlAttribute('autocomplete', 'off');

		$form->addSubmit('submit', 'Odeslat zprávu');
	}

	public function processForm(ArrayHash $values): void
	{
		if ($values->website !== '') {
			$this->presenter->redirect('this');
		}

		$contactMessage = new ContactMessage();
		$contactMessage
			->setName($values->name)
			->setEmail($values->email)
			->setMessage($values->message);

		$this->em->persist($contactMessage);
		$this->em->flush($contactMessage);

		$this->sendContactMessage($contactMessage);

		$this->presenter->flashMessageSuccess('Děkujeme za zprávu, ozveme se vám co nejdříve.');
		$this->presenter->redirect('this');
	}

	protected function sendContactMessage(ContactMessage $contactMessage): void
	{
		$subject = 'Zpráva z webu sk-alpin.cz';

		$body = '
			<html lang="cs">
				<head>
					<meta charset="utf-8">
					<title>' . $subject . '</title>
				</head>
				<body>
					<p>Obdržena zpráva od ' . htmlspecialchars($contactMessage->getName()) . ' (' . htmlspecialchars($contactMessage->getEmail()) . ')</p>
					<p>Text zprávy:</p>
					<p>' . nl2br(htmlspecialchars($contactMessage->getMessage())) . '</p>
					<hr>
					<small><i>E-mail odeslán prostřednictvím kontaktního formuláře na webu sk-alpin.cz.</i></small>
				</body>
			</html>
		';

		$mail = new Message;
		$mail->setFrom($this->noReplyEmail)
			->addTo($this->contactEmail)
			->addReplyTo($contactMessage->getEmail())
			->setSubject($subject)
			->setHtmlBody($body);

		$this->mailer->send($mail);
	}

}
