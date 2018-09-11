<?php

declare(strict_types=1);


\call_user_func(function (): void {
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/bootstrap.inc';

	\date_default_timezone_set('Europe/Prague');
	\Tester\Environment::setup();

	\define('TEMP_DIR', __DIR__ . '/temp/' . \random_int(0, \PHP_INT_MAX));
	\Nette\Utils\FileSystem::createDir(\dirname(\TEMP_DIR));
	\Tester\Helpers::purge(\TEMP_DIR);
});
