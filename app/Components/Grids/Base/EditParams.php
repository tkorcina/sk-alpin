<?php

namespace App\Components\Grids\Base;

class EditParams
{
	public function __construct(string $redirect)
	{
		$this->redirect = $redirect;
	}
}
