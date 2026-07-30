<?php

namespace App\Modules\AdminModule;

abstract class BasePresenter extends \App\Modules\BasePresenter
{

	public function startup(): void
	{
		parent::startup();

		if (!$this->isLogged()) {
			$this->flashMessageError('Pro přístup do této sekce musíte být přihlášen.');
			$this->redirect('Sign:in');
		}
	}

}
