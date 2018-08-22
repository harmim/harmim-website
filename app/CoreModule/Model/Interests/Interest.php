<?php

declare(strict_types=1);

namespace App\CoreModule\Model\Interests;

final class Interest
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $interest;

	/**
	 * @var string|null
	 */
	private $faIcon;

	/**
	 * @var string|null
	 */
	private $materialIcon;


	public function __construct(string $interest, ?string $faIcon = null, ?string $materialIcon = null)
	{
		$this->interest = $interest;
		$this->faIcon = $faIcon;
		$this->materialIcon = $materialIcon;
	}


	public function getInterest(): string
	{
		return $this->interest;
	}


	public function getFaIcon(): ?string
	{
		return $this->faIcon;
	}


	public function getMaterialIcon(): ?string
	{
		return $this->materialIcon;
	}
}
