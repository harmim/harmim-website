<?php

declare(strict_types=1);

namespace Dh\Application\UI;

/**
 * @method \Nette\Bridges\ApplicationLatte\Template getTemplate()
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	/**
	 * @var \Kdyby\Translation\ITranslator
	 */
	protected $translator;

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


	public function formatTemplateFiles(): array
	{
		$viewTemplates = $this->templateLocator->formatViewTemplate((string) $this->getName(), $this->getView());

		return $viewTemplates ?: parent::formatTemplateFiles();
	}


	public function formatLayoutTemplateFiles(): array
	{
		$layoutTemplates = $this->templateLocator->formatLayoutTemplate(
			(string) $this->getName(),
			(string) $this->getLayout()
		);

		return $layoutTemplates ?: parent::formatLayoutTemplateFiles();
	}
}
