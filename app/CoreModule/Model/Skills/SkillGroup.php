<?php

declare(strict_types=1);

namespace App\CoreModule\Model\Skills;

final class SkillGroup
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var array|\App\CoreModule\Model\Skills\Skill[]
	 */
	private $skills;


	/**
	 * @param array|\App\CoreModule\Model\Skills\Skill[] $skills
	 */
	public function __construct(string $title, array $skills)
	{
		$this->title = $title;
		$this->skills = $skills;
	}


	public function getTitle(): string
	{
		return $this->title;
	}


	/**
	 * @return array|\App\CoreModule\Model\Skills\Skill[]
	 */
	public function getSkills(): array
	{
		return $this->skills;
	}
}
