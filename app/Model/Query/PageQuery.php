<?php
declare(strict_types=1);

namespace App\Model\Query;

use App\Model\Entity\Page;

/**
 * @extends BaseQuery<Page>
 */
class PageQuery extends BaseQuery
{
	protected ?string $entityClass = Page::class;

	public function byInternalName(string $internalName): static
	{
		return $this->by('internalName', $internalName);
	}

}
