<?php

declare(strict_types=1);

namespace Dh\Application\Templates;

final class TemplateLocator implements \Dh\Application\Templates\ITemplateLocator
{
	use \Nette\SmartObject;

	/**
	 * @var string[]
	 */
	private $dirs;

	/**
	 * @var string
	 */
	private $defaultModuleWithLayout;


	public function __construct(array $dirs, string $defaultModuleWithLayout)
	{
		$this->dirs = $dirs;
		$this->defaultModuleWithLayout = $defaultModuleWithLayout;
	}


	/**
	 * @throws \InvalidArgumentException
	 */
	public function formatViewTemplate(string $presenterName, string $view): array
	{
		if (!\preg_match('~^([[:alnum:]]+):([[:alnum:]]+)\z~', $presenterName, $presenterNameMatches)) {
			throw new \InvalidArgumentException("Invalid presenter name: $presenterName.");
		}

		$paths = [];
		$paths[] = "/$presenterNameMatches[1]Module/Presenters/$view.latte";
		$paths[] = "/$presenterNameMatches[1]Module/templates/$presenterNameMatches[2]/$view.latte";

		$list = [];
		foreach ($this->dirs as $dir) {
			foreach ($paths as $path) {
				$list[] = $dir . $path;
			}
		}

		return $list;
	}


	/**
	 * @throws \InvalidArgumentException
	 */
	public function formatLayoutTemplate(string $presenterName, string $layout = ''): array
	{
		if ($layout && \is_readable($layout)) {
			return [$layout];
		}

		if (!\preg_match('~^([[:alnum:]]+):([[:alnum:]]+)\z~', $presenterName, $presenterNameMatches)) {
			throw new \InvalidArgumentException("Invalid presenter name: $presenterName.");
		}
		$layout = $layout ?: 'layout';

		$paths = [];
		$paths[] = "/$presenterNameMatches[1]Module/templates/$presenterNameMatches[2]/@$layout.latte";
		$paths[] = "/$presenterNameMatches[1]Module/templates/@$layout.latte";
		if ($presenterNameMatches[1] !== $this->defaultModuleWithLayout) {
			$paths[] = "/{$this->defaultModuleWithLayout}Module/templates/@$layout.latte";
		}

		$list = [];
		foreach ($this->dirs as $dir) {
			foreach ($paths as $path) {
				$list[] = $dir . $path;
			}
		}

		return $list;
	}


	public function formatControlTemplate(string $controlName, string $controlDir, ?string $templateName = null): array
	{
		if (!$templateName) {
			$templateName = \lcfirst($controlName) . '.latte';
		} elseif (!\Nette\Utils\Strings::endsWith($templateName, '.latte')) {
			$templateName .= '.latte';
		}

		$list = [];
		foreach ($this->dirs as $dir) {
			if (\Nette\Utils\Strings::startsWith($controlDir, $dir)) {
				$list[] = "$controlDir/$templateName";

				$controlDir = \substr($controlDir, \strlen((string) \realpath($dir)));
				$endString = 'Controls' . \DIRECTORY_SEPARATOR . \ucfirst($controlName);
				if (\Nette\Utils\Strings::endsWith($controlDir, $endString)) {
					$list[] = $dir
						. \substr($controlDir, 0, -\strlen($endString))
						. 'templates'
						. \DIRECTORY_SEPARATOR
						. 'controls'
						. \DIRECTORY_SEPARATOR
						. \ucfirst($controlName)
						. \DIRECTORY_SEPARATOR
						. $templateName;
				}
			}
		}

		return $list;
	}
}
