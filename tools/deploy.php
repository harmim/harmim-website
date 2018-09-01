<?php

declare(strict_types=1);


require __DIR__ . '/deploy.inc';


echo "Deploy started.\n\n";

$exitCode = 0;
$rootDir = (string) \realpath(__DIR__ . '/..');
$vendorDir = "$rootDir/vendor";

$options = '';
if ($argc > 1) {
	\array_shift($argv);
	$options = \implode(' ', $argv);
}

try {
	\doProcess('make PRODUCTION=1', $rootDir, 'Make production');

	$cmd = "./deployment $rootDir/tools/deployment.ini" . ($options ? " $options" : '');
	\doProcess($cmd, "$vendorDir/bin", 'FTP deployment');

	\doProcess('make', $rootDir, 'Make development');

} catch (\Exception $e) {
	echo "{$e->getCode()} - {$e->getMessage()}\n\n";
	$exitCode = 1;

} finally {
	echo 'Dploy finished ' . ($exitCode === 0 ? 'successful' : 'unsuccessful') . ".\n\n";
	exit($exitCode);
}
