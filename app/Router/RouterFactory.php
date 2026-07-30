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
		$publicModule[] = new Route('[<locale cs|en>/]o-nas', ['presenter' => 'Page', 'action' => 'default', 'internalName' => 'about']);
		$publicModule[] = new Route('[<locale cs|en>/]akce', 'Event:default');
		$publicModule[] = new Route('[<locale cs|en>/]akce/<slug>', 'Event:detail');
		$publicModule[] = new Route('[<locale cs|en>/]aktuality', 'News:default');
		$publicModule[] = new Route('[<locale cs|en>/]aktuality/<slug>', 'News:detail');
		$publicModule[] = new Route('[<locale cs|en>/]fotogalerie', 'Gallery:default');
		$publicModule[] = new Route('[<locale cs|en>/]fotogalerie/<slug>', 'Gallery:detail');
		$publicModule[] = new Route('[<locale cs|en>/]kontakt', 'Contact:default');
		$publicModule[] = new Route('[<locale cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
		$publicModule[] = new Route('/', 'Homepage:default');

		return $router;
	}

}
