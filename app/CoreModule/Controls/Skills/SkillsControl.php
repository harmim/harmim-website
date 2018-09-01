<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Skills;

final class SkillsControl extends \Dh\Application\UI\BaseControl
{
	/**
	 * @var array
	 */
	private $skills;


	public function __construct(array $skills)
	{
		parent::__construct();
		$this->skills = $skills;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->add('skillGroups', $this->getSkillGroups());
	}


	/**
	 * @return array|\App\CoreModule\Model\Skills\SkillGroup[]
	 */
	private function getSkillGroups(): array
	{
		$skillGroups = [];
		foreach ($this->skills as $skillGroup) {
			$skillGroups[] = new \App\CoreModule\Model\Skills\SkillGroup(
				$this->translator->translate($skillGroup['title']),
				$this->getSkills($skillGroup['skills'])
			);
		}

		return $skillGroups;
	}


	/**
	 * @return array|\App\CoreModule\Model\Skills\Skill[]
	 */
	private function getSkills(array $skills): array
	{
		$result = [];
		foreach ($skills as $skill) {
			$result[] = new \App\CoreModule\Model\Skills\Skill(
				$skill['title'],
				$skill['percentage']
			);
		}

		return $result;
	}
}
