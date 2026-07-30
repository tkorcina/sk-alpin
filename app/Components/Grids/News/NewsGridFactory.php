<?php

namespace App\Components\Grids\News;

interface NewsGridFactory
{
	public function create(): NewsGrid;
}
