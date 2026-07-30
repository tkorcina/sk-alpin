<?php

namespace App\Components\Grids\ContactMessage;

interface ContactMessageGridFactory
{
	public function create(): ContactMessageGrid;
}
