<?php

namespace App\Components\Forms\Event;

interface EventFormFactory
{
	public function create(): EventForm;
}
