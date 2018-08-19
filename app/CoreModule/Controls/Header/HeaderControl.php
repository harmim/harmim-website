<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Header;

final class HeaderControl extends \Dh\Application\UI\BaseControl
{
	/**
	 * @var array
	 */
	private $links;


	public function __construct(array $links)
	{
		parent::__construct();
		$this->links = $links;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$links = $this->getLinks();
		$this->getTemplate()->add('links', $links);
		$this->getTemplate()->add('firstLink', \count($links) ? \reset($links) : null);
	}


	private function getLinks(): array
	{
		$links = [];
		foreach ($this->links as $link) {
			$links[] = new \App\CoreModule\Model\Header\Link(
				$this->translator->translate($link['id']),
				$this->translator->translate($link['name']),
				$link['icon'] ?? null
			);
		}

		return $links;
	}
}
