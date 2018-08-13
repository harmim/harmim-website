<?php

declare(strict_types=1);


return \call_user_func(function (): \Nette\DI\Container {
	require __DIR__ . '/../vendor/autoload.php';

	$rootDir = \realpath(__DIR__ . '/..');
	$logDir = "$rootDir/log";
	$tempDir = "$rootDir/temp";

	$configurator = new \Dh\Config\Configurator();
	$configurator->addParameters([
		'rootDir' => $rootDir,
		'appDir' => "$rootDir/app",
		'logDir' => $logDir,
		'tempDir' => $tempDir,
		'wwwDir' => "$rootDir/www",
	]);
	$configurator->setTimeZone('Europe/Prague');
	$configurator->setTempDirectory($tempDir);
	$configurator->setDebugMode($configurator->dhDetectDebugMode([
		'91.224.48.134',
		'84.42.169.108',
	]));
	$configurator->enableTracy($logDir);
	$configurator->loadConfigs(['common']);

	return $configurator->createContainer();
});
