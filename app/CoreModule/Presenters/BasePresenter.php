<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

abstract class BasePresenter extends \Dh\Application\UI\BasePresenter
{
	/**
	 * @var string
	 * @persistent
	 */
	public $locale;

	/**
	 * @var \App\CoreModule\Controls\Head\IHeadControlFactory
	 */
	private $headControlFactory;

	/**
	 * @var \App\CoreModule\Controls\PreLoader\IPreLoaderControlFactory
	 */
	private $preLoaderControlFactory;

	/**
	 * @var \App\CoreModule\Controls\Header\Homepage\IHomepageHeaderControlFactory
	 */
	private $headerControlFactory;

	/**
	 * @var \App\CoreModule\Controls\ScrollToTop\IScrollToTopControlFactory
	 */
	private $scrollToTopControlFactory;

	/**
	 * @var \App\CoreModule\Controls\Footer\IFooterControlFactory
	 */
	private $footerControlFactory;


	public function injectHeadControlFactory(
		\App\CoreModule\Controls\Head\IHeadControlFactory $headControlFactory
	): void {
		$this->headControlFactory = $headControlFactory;
	}


	public function injectPreLoaderControlFactory(
		\App\CoreModule\Controls\PreLoader\IPreLoaderControlFactory $preLoaderControlFactory
	): void {
		$this->preLoaderControlFactory = $preLoaderControlFactory;
	}


	public function injectHomepageHeaderControlFactory(
		\App\CoreModule\Controls\Header\Homepage\IHomepageHeaderControlFactory $homepageHeaderControlFactory
	): void {
		$this->headerControlFactory = $homepageHeaderControlFactory;
	}


	public function injectScrollToTopControlFactory(
		\App\CoreModule\Controls\ScrollToTop\IScrollToTopControlFactory $scrollToTopControlFactory
	): void {
		$this->scrollToTopControlFactory = $scrollToTopControlFactory;
	}


	public function injectFooterControlFactory(
		\App\CoreModule\Controls\Footer\IFooterControlFactory $footerControlFactory
	): void {
		$this->footerControlFactory = $footerControlFactory;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->add('locale', $this->locale);
	}


	protected function createComponentHead(): \App\CoreModule\Controls\Head\HeadControl
	{
		return $this->headControlFactory->create();
	}


	protected function createComponentPreLoader(): \App\CoreModule\Controls\PreLoader\PreLoaderControl
	{
		return $this->preLoaderControlFactory->create();
	}


	protected function createComponentHeader(): \App\CoreModule\Controls\Header\HeaderControl
	{
		return $this->headerControlFactory->create();
	}


	protected function createComponentScrollToTop(): \App\CoreModule\Controls\ScrollToTop\ScrollToTopControl
	{
		return $this->scrollToTopControlFactory->create();
	}


	protected function createComponentFooter(): \App\CoreModule\Controls\Footer\FooterControl
	{
		return $this->footerControlFactory->create();
	}
}
