<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Header\Homepage;

final class HomepageHeaderControlFactory implements
	\App\CoreModule\Controls\Header\Homepage\IHomepageHeaderControlFactory
{
	use \Nette\SmartObject;

	/**
	 * @var array
	 */
	private $links;

	/**
	 * @var \App\CoreModule\Controls\Header\IHeaderControlFactory
	 */
	private $headerControlFactory;


	public function __construct(
		array $links,
		\App\CoreModule\Controls\Header\IHeaderControlFactory $headerControlFactory
	) {
		$this->links = $links;
		$this->headerControlFactory = $headerControlFactory;
	}


	public function create(): \App\CoreModule\Controls\Header\HeaderControl
	{
		return $this->headerControlFactory->create($this->links);
	}
}
