<?php

declare(strict_types=1);

namespace App\CoreModule\Model\Header;

final class Link
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string|null
	 */
	private $icon;


	public function __construct(string $id, string $name, ?string $icon = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->icon = $icon;
	}


	public function getId(): string
	{
		return $this->id;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getIcon(): ?string
	{
		return $this->icon;
	}
}
