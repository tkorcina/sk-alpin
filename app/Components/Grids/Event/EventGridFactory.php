<?php

namespace App\Components\Grids\Event;

interface EventGridFactory
{
	public function create(): EventGrid;
}
