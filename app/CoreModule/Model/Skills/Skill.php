<?php

declare(strict_types=1);

namespace App\CoreModule\Model\Skills;

final class Skill
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var int
	 */
	private $percentage;


	public function __construct(string $title, int $percentage)
	{
		$this->title = $title;
		$this->percentage = $percentage;
	}


	public function getTitle(): string
	{
		return $this->title;
	}


	public function getPercentage(): int
	{
		return $this->percentage;
	}
}
