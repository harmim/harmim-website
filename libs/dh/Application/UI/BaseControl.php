<?php

declare(strict_types=1);

namespace Dh\Application\UI;

/**
 * @method \Nette\Bridges\ApplicationLatte\Template getTemplate()
 */
abstract class BaseControl extends \Nette\Application\UI\Control
{
	/**
	 * @var \Kdyby\Translation\ITranslator
	 */
	protected $translator;

	/**
	 * @var string|null
	 */
	private $templateName;

	/**
	 * @var \Dh\Application\Templates\ITemplateLocator
	 */
	private $templateLocator;


	public function injectTranslator(\Kdyby\Translation\ITranslator $translator): void
	{
		$this->translator = $translator;
	}


	public function injectTemplateLocator(\Dh\Application\Templates\ITemplateLocator $templateLocator): void
	{
		$this->templateLocator = $templateLocator;
	}


	public function getTemplateName(): ?string
	{
		return $this->templateName;
	}


	public function setTemplateName(?string $templateName): void
	{
		$this->templateName = $templateName;
	}


	protected function beforeRender(): void
	{
	}


	/**
	 * @throws \ReflectionException
	 */
	private function renderTemplate(?string $templateName = null, array $args = []): void
	{
		if (!$templateName) {
			if ($this->templateName) {
				$templateName = $this->templateName;
			}
		} else {
			$this->templateName = $templateName;
		}

		$this->setTemplateFile($templateName);
		$this->setTemplateParameters($args);
		$this->beforeRender();
		$this->getTemplate()->render();
	}


	private function setTemplateParameters(array $args = []): void
	{
		foreach ($args as $key => $value) {
			$this->getTemplate()->add($key, $value);
		}
	}


	/**
	 * @throws \ReflectionException
	 */
	private function setTemplateFile(?string $templateName = null): void
	{
		$controlDir = \dirname((string) (new \ReflectionClass($this))->getFileName());
		$files = $this->templateLocator->formatControlTemplate((string) $this->getName(), $controlDir, $templateName);

		foreach ($files as &$file) {
			$file = \strtr($file, '/', \DIRECTORY_SEPARATOR);
			if (\is_readable($file)) {
				$this->getTemplate()->setFile($file);

				return;
			}
		}

		throw new \Nette\FileNotFoundException(
			\sprintf('Neither of control templates was found %s.', \implode(', ', $files))
		);
	}


	/**
	 * @return mixed|static
	 *
	 * @throws \Nette\MemberAccessException
	 * @throws \ReflectionException
	 */
	public function __call($name, $args)
	{
		if (\substr($name, 0, 6) === 'render') {
			$templateName = \strlen($name) > 6 ? \lcfirst(\substr($name, 6)) . '.latte' : null;
			if (\count($args) && \is_array($args[0])) {
				$args = $args[0];
			}
			$this->renderTemplate($templateName, $args);

			return $this;
		}

		return parent::__call($name, $args);
	}
}
