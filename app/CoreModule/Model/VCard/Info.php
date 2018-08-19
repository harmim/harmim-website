<?php

declare(strict_types=1);

namespace App\CoreModule\Model\VCard;

final class Info
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var string|null
	 */
	private $link;


	public function __construct(string $icon, string $content, ?string $link = null)
	{
		$this->icon = $icon;
		$this->content = $content;
		$this->link = $link;
	}


	public function getIcon(): string
	{
		return $this->icon;
	}


	public function getContent(): string
	{
		return $this->content;
	}


	public function getLink(): ?string
	{
		return $this->link;
	}
}
