<?php

declare(strict_types=1);


return \call_user_func(function (): \Nette\DI\Container {
	require __DIR__ . '/../vendor/autoload.php';

	$rootDir = (string) \realpath(__DIR__ . '/..');

	$configurator = new \Dh\Configurator\Configurator($rootDir);
	$configurator->setTimeZone('Europe/Prague');
	$configurator->setTempDirectory("$rootDir/temp");
	$configurator->setDebugMode(\Dh\Configurator\Helpers::detectDebugMode([
		'91.224.48.134',
		'84.42.169.108',
	]));
	$configurator->enableTracy("$rootDir/log");
	$configurator->loadConfigs(['common']);

	return $configurator->createContainer();
});
