<?php

namespace App\Components\Grids\Config;

interface ConfigGridFactory
{
	public function create(): ConfigGrid;
}
