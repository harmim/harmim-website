<?php

declare(strict_types=1);

/**
 * Test: \App\Tests\CoreModule\Model\Header\LinkTest.
 *
 * @testCase
 */

namespace App\Tests\CoreModule\Model\Header;

require __DIR__ . '/../../../../bootstrap.php';


final class LinkTest extends \Tester\TestCase
{
	public function testLinkGetters(): void
	{
		$link = new \App\CoreModule\Model\Header\Link('foo', 'Foo');
		\Tester\Assert::same('foo', $link->getId());
		\Tester\Assert::same('Foo', $link->getName());
		\Tester\Assert::same(null, $link->getIcon());

		$link = new \App\CoreModule\Model\Header\Link('bar', 'Bar', 'fa-bar');
		\Tester\Assert::same('bar', $link->getId());
		\Tester\Assert::same('Bar', $link->getName());
		\Tester\Assert::same('fa-bar', $link->getIcon());
	}
}


run(new \App\Tests\CoreModule\Model\Header\LinkTest());
