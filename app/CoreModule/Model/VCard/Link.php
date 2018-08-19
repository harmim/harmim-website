<?php

declare(strict_types=1);

namespace App\CoreModule\Model\VCard;

final class Link
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $link;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * @var string|null
	 */
	private $class;


	public function __construct(string $link, string $icon, ?string $class = null)
	{
		$this->link = $link;
		$this->icon = $icon;
		$this->class = $class;
	}


	public function getLink(): string
	{
		return $this->link;
	}


	public function getIcon(): string
	{
		return $this->icon;
	}


	public function getClass(): ?string
	{
		return $this->class;
	}
}
