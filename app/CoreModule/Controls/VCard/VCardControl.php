<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\VCard;

final class VCardControl extends \Dh\Application\UI\BaseControl
{
	/**
	 * @var array
	 */
	private $infos;

	/**
	 * @var array
	 */
	private $links;


	public function __construct(array $infos, array $links)
	{
		parent::__construct();
		$this->infos = $infos;
		$this->links = $links;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->add('infos', $this->getInfos());
		$this->getTemplate()->add('links', $this->getLinks());
	}


	/**
	 * @return array|\App\CoreModule\Model\VCard\Info[]
	 */
	private function getInfos(): array
	{
		$infos = [];
		foreach ($this->infos as $info) {
			$infos[] = new \App\CoreModule\Model\VCard\Info(
				$info['icon'],
				$info['content'],
				$info['link'] ?? null
			);
		}

		return $infos;
	}


	/**
	 * @return array|\App\CoreModule\Model\VCard\Link[]
	 */
	private function getLinks(): array
	{
		$links = [];
		foreach ($this->links as $link) {
			$links[] = new \App\CoreModule\Model\VCard\Link(
				$link['link'],
				$link['icon'],
				$link['class'] ?? null
			);
		}

		return $links;
	}
}
