<?php

declare(strict_types=1);


function doProcess(string $cmd, string $dir, string $name): void
{
	echo "$name started.\n\n";

	\chdir($dir);
	$process = \popen($cmd, 'r');
	\ob_start(null, 4096);
	while (!\feof($process)) {
		echo \fread($process, 4096);
		\ob_flush();
	}
	\ob_end_clean();

	echo "$name finished.\n\n";
}


echo "Deploy started.\n\n";

$exitCode = 0;
$rootDir = \realpath(__DIR__ . '/..');
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
