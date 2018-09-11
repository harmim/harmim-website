<?php

declare(strict_types=1);

/**
 * Test: \Dh\Tests\Application\Templates\TemplateLocatorTest.
 *
 * @testCase
 */

namespace Dh\Tests\Application\Templates;

require __DIR__ . '/../../../bootstrap.php';


final class TemplateLocatorTest extends \Tester\TestCase
{
	/**
	 * @var \Dh\Application\Templates\ITemplateLocator
	 */
	private $emptyTemplateLocator;

	/**
	 * @var \Dh\Application\Templates\ITemplateLocator
	 */
	private $templateLocator;


	protected function setUp(): void
	{
		$this->emptyTemplateLocator = new \Dh\Application\Templates\TemplateLocator([], '');
		$this->templateLocator = new \Dh\Application\Templates\TemplateLocator(['app', 'foo'], 'Core');
	}


	public function testFormatViewTemplate(): void
	{
		\Tester\Assert::exception(function (): void {
			$this->emptyTemplateLocator->formatViewTemplate('', '');
		}, \InvalidArgumentException::class);

		\Tester\Assert::exception(function (): void {
			$this->templateLocator->formatViewTemplate('', '');
		}, \InvalidArgumentException::class);

		\Tester\Assert::exception(function (): void {
			$this->emptyTemplateLocator->formatViewTemplate('Foo', 'default');
		}, \InvalidArgumentException::class);

		\Tester\Assert::exception(function (): void {
			$this->templateLocator->formatViewTemplate('Foo', 'default');
		}, \InvalidArgumentException::class);


		\Tester\Assert::same([], $this->emptyTemplateLocator->formatViewTemplate('Core:Homepage', 'default'));

		\Tester\Assert::same([
			'app/CoreModule/Presenters/default.latte',
			'app/CoreModule/templates/Homepage/default.latte',
			'foo/CoreModule/Presenters/default.latte',
			'foo/CoreModule/templates/Homepage/default.latte',
		], $this->templateLocator->formatViewTemplate('Core:Homepage', 'default'));
	}


	public function testFormatLayoutTemplate(): void
	{
		\Tester\Assert::exception(function (): void {
			$this->emptyTemplateLocator->formatLayoutTemplate('');
		}, \InvalidArgumentException::class);

		\Tester\Assert::exception(function (): void {
			$this->templateLocator->formatLayoutTemplate('');
		}, \InvalidArgumentException::class);

		\Tester\Assert::exception(function (): void {
			$this->emptyTemplateLocator->formatLayoutTemplate('Foo');
		}, \InvalidArgumentException::class);

		\Tester\Assert::exception(function (): void {
			$this->templateLocator->formatLayoutTemplate('Foo');
		}, \InvalidArgumentException::class);


		\Tester\Assert::same([], $this->emptyTemplateLocator->formatLayoutTemplate('Core:Homepage'));
		\Tester\Assert::same([], $this->emptyTemplateLocator->formatLayoutTemplate('Core:Homepage', 'barLayout'));

		\Tester\Assert::same([
			'app/CoreModule/templates/Homepage/@layout.latte',
			'app/CoreModule/templates/@layout.latte',
			'foo/CoreModule/templates/Homepage/@layout.latte',
			'foo/CoreModule/templates/@layout.latte',
		], $this->templateLocator->formatLayoutTemplate('Core:Homepage'));

		\Tester\Assert::same([
			'app/CoreModule/templates/Homepage/@barLayout.latte',
			'app/CoreModule/templates/@barLayout.latte',
			'foo/CoreModule/templates/Homepage/@barLayout.latte',
			'foo/CoreModule/templates/@barLayout.latte',
		], $this->templateLocator->formatLayoutTemplate('Core:Homepage', 'barLayout'));

		\Tester\Assert::same([
			'app/UserModule/templates/Homepage/@layout.latte',
			'app/UserModule/templates/@layout.latte',
			'app/CoreModule/templates/@layout.latte',
			'foo/UserModule/templates/Homepage/@layout.latte',
			'foo/UserModule/templates/@layout.latte',
			'foo/CoreModule/templates/@layout.latte',
		], $this->templateLocator->formatLayoutTemplate('User:Homepage'));

		\Tester\Assert::same([
			'app/UserModule/templates/Homepage/@barLayout.latte',
			'app/UserModule/templates/@barLayout.latte',
			'app/CoreModule/templates/@barLayout.latte',
			'foo/UserModule/templates/Homepage/@barLayout.latte',
			'foo/UserModule/templates/@barLayout.latte',
			'foo/CoreModule/templates/@barLayout.latte',
		], $this->templateLocator->formatLayoutTemplate('User:Homepage', 'barLayout'));


		$layoutFile = \Tester\FileMock::create('');

		\Tester\Assert::same(
			[$layoutFile],
			$this->emptyTemplateLocator->formatLayoutTemplate('Core:Homepage', $layoutFile)
		);

		\Tester\Assert::same(
			[$layoutFile],
			$this->templateLocator->formatLayoutTemplate('Core:Homepage', $layoutFile)
		);
	}
}


run(new \Dh\Tests\Application\Templates\TemplateLocatorTest());
