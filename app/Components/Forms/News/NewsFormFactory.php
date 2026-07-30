<?php

namespace App\Components\Forms\News;

interface NewsFormFactory
{
	public function create(): NewsForm;
}
