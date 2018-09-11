<?php

declare(strict_types=1);


function test(\Closure $closure): void
{
	$closure();
}


function run(\Tester\TestCase $testCase): void
{
	/** @noinspection PhpUnhandledExceptionInspection */
	$testCase->run();
}
