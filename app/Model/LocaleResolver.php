<?php

declare(strict_types=1);

namespace App\Model;

use Contributte\Translation\LocalesResolvers\ResolverInterface;
use Contributte\Translation\Translator;

class LocaleResolver implements ResolverInterface
{
	public function resolve(Translator $translator): ?string
	{
		return $translator->getDefaultLocale();
	}
}
