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

	$processExitCode = \pclose($process);
	if ($processExitCode !== 0) {
		throw new \RuntimeException("Process '$cmd' failed.", $processExitCode);
	}

	echo "$name finished.\n\n";
}
