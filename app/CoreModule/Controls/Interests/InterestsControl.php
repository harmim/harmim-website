<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Interests;

final class InterestsControl extends \Dh\Application\UI\BaseControl
{
	/**
	 * @var array
	 */
	private $interests;


	public function __construct(array $interests)
	{
		parent::__construct();
		$this->interests = $interests;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->add('interests', $this->getInterests());
	}


	/**
	 * @return array|\App\CoreModule\Model\Interests\Interest[]
	 */
	private function getInterests(): array
	{
		$interests = [];
		foreach ($this->interests as $interest) {
			$interests[] = new \App\CoreModule\Model\Interests\Interest(
				$this->translator->translate($interest['interest']),
				$interest['faIcon'] ?? null,
				$interest['materialIcon'] ?? null
			);
		}

		return $interests;
	}
}
