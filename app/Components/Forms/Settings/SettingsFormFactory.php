<?php

declare(strict_types=1);

namespace App\Components\Forms\Settings;

interface SettingsFormFactory
{
	public function create(): SettingsForm;
}
