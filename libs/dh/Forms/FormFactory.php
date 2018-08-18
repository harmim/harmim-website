<?php

declare(strict_types=1);

namespace Dh\Forms;

final class FormFactory implements \Dh\Forms\IFormFactory
{
	use \Nette\SmartObject;

	/**
	 * @var \Kdyby\Translation\ITranslator
	 */
	private $translator;


	public function __construct(\Kdyby\Translation\ITranslator $translator)
	{
		$this->translator = $translator;
	}


	public function create(
		\Nette\ComponentModel\IContainer $parent = null,
		string $name = null
	): \Nette\Application\UI\Form {
		$form = new \Nette\Application\UI\Form($parent, $name);
		$form->setTranslator($this->translator);
		$form->onError[] = [$this, 'onErrorHandler'];

		return $form;
	}


	/**
	 * @internal
	 */
	public function onErrorHandler(\Nette\Application\UI\Form $form): void
	{
		if ($form->getParent() && isset($form->getParent()['flashMessage'])) {
			$component = $form->getParent();
		} elseif ($form->getPresenter() && isset($form->getPresenter()['flashMessage'])) {
			$component = $form->getPresenter();
		}

		if (isset($component) && \method_exists($component, 'flashMessage')) {
			foreach ($form->getErrors() as $error) {
				$component->flashMessage($error, 'error');
			}
		}
	}
}
