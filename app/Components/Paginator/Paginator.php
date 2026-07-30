<?php

namespace App\Components\Controls\Paginator;

class Paginator extends \Nette\Application\UI\Control
{

	public function __construct(protected \Nette\Utils\Paginator $paginator)
	{}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/Paginator.latte');
		$this->template->paginator = $this->paginator;
		$this->template->render();
	}

}