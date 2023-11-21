<?php

declare(strict_types=1);

namespace app\Base\Router;

use Apitte\Presenter\ApiRoute;
use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('<presenter>/<action>[/<id>]', [
            'presenter' => 'Homepage',
            'action' => 'Default'
        ]);
		return $router;
	}
}
