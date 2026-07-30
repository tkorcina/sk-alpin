<?php

namespace App\Components\Forms\Config;

interface ConfigFormFactory
{
	public function create(): ConfigForm;
}
