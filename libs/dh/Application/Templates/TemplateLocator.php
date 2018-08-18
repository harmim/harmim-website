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


	public function formatViewTemplate(string $presenterName, string $view): array
	{
		$part = \explode(':', $presenterName);

		$paths = [];
		$paths[] = "/$part[0]Module/Presenters/$view.latte";
		$paths[] = "/$part[0]Module/templates/$part[1]/$view.latte";

		$list = [];
		foreach ($this->dirs as $dir) {
			foreach ($paths as $path) {
				$list[] = $dir . $path;
			}
		}

		return $list;
	}


	public function formatLayoutTemplate(string $presenterName, string $layout = ''): array
	{
		if ($layout && \is_readable($layout)) {
			return [$layout];
		}

		$layout = $layout ?: 'layout';
		$part = \explode(':', $presenterName);

		$paths = [];
		$paths[] = "/$part[0]Module/templates/$part[1]/@$layout.latte";
		$paths[] = "/$part[0]Module/templates/@$layout.latte";
		if ($part[0] !== $this->defaultModuleWithLayout) {
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
