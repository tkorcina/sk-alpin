<?php
declare(strict_types=1);

namespace App\Model\Query;

interface GalleryQueryFactory
{
	function create(): GalleryQuery;
}
