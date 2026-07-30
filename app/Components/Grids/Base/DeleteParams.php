<?php

namespace App\Components\Grids\Base;

use Closure;

class DeleteParams
{
	public ?Closure $onDelete = null;

	public function __construct(?Closure $onDelete = null)
	{
		$this->onDelete = $onDelete;
	}

}
