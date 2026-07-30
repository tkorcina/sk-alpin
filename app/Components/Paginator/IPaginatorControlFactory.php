<?php
declare(strict_types=1);


namespace App\Components\Controls\Paginator;

interface IPaginatorControlFactory
{
	public function create(\Nette\Utils\Paginator $paginator): Paginator;
}