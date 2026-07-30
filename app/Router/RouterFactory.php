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
		$adminModule[] = new Route('[<locale cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
		$adminModule[] = new Route('/', 'Homepage:default');

		return $router;
	}

}
