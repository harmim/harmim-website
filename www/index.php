<?php

declare(strict_types=1);


/** @var \Nette\DI\Container $container */
$container = require __DIR__ . '/../app/bootstrap.php';
/** @var \Nette\Application\Application $application */
$application = $container->getByType(\Nette\Application\Application::class);

/** @noinspection PhpUnhandledExceptionInspection */
$application->run();
