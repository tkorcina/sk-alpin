<?php

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory
{

	public function __construct(
		private Nette\Http\Request $request,
	)
	{}

	public function createRouter(): RouteList
	{
		$router = new RouteList;

		$adminModule = new RouteList('Admin');
		$router[] = $adminModule;
		$adminModule[] = new Route('administrace/[<locale cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');

		$publicModule = new RouteList('Public');
		$router[] = $publicModule;
		$publicModule[] = new Route('[<locale cs|en>/]sitemap[.xml]', 'Homepage:sitemap');
		$publicModule[] = new Route('[<locale cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
		$publicModule[] = new Route('/', 'Homepage:default');

		return $router;
	}

}
