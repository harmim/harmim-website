<?php

declare(strict_types=1);

namespace App\CoreModule\Model\Router;

final class RouterFactory
{
	use \Nette\StaticClass;

	private const MASK_LOCALE = '<locale=cs cs|en>';


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public static function createRouter(): \Nette\Application\IRouter
	{
		$router = new \Nette\Application\Routers\RouteList();
		$router[] = new \Nette\Application\Routers\Route('[' . self::MASK_LOCALE . ']', 'Core:Homepage:default');

		return $router;
	}
}
