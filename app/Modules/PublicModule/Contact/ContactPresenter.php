<?php

namespace App\Modules\PublicModule\Contact;

use App\Components\Forms\Contact\ContactForm;
use App\Components\Forms\Contact\ContactFormFactory;
use App\Model\Entity\Enum\Page;
use App\Modules\PublicModule\BasePresenter;

class ContactPresenter extends BasePresenter
{

	public function actionDefault(): void
	{
		$this->loadPage(Page::PAGE_CONTACT_INTERNAL_NAME);
	}

	public function renderDefault(): void
	{
		$this->template->page = $this->page;
	}

	public function createComponentContactForm(ContactFormFactory $factory): ContactForm
	{
		return $factory->create();
	}

}
