<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

final class HomepagePresenter extends \App\CoreModule\Presenters\BasePresenter
{
	/**
	 * @var \Dh\Config\IConfigService
	 */
	private $configService;

	/**
	 * @var \App\CoreModule\Controls\Header\IHeaderControlFactory
	 */
	private $headerControlFactory;

	/**
	 * @var \App\CoreModule\Controls\About\IAboutControlFactory
	 */
	private $aboutControlFactory;

	/**
	 * @var \App\CoreModule\Controls\VCard\IVCardControlFactory
	 */
	private $vCardControlFactory;

	/**
	 * @var \App\CoreModule\Controls\Skills\ISkillsControlFactory
	 */
	private $skillsControlFactory;

	/**
	 * @var \App\CoreModule\Controls\Interests\IInterestsControlFactory
	 */
	private $interestsControlFactory;


	public function __construct(
		\Dh\Config\IConfigService $configService,
		\App\CoreModule\Controls\Header\IHeaderControlFactory $headerControlFactory,
		\App\CoreModule\Controls\About\IAboutControlFactory $aboutControlFactory,
		\App\CoreModule\Controls\VCard\IVCardControlFactory $vCardControlFactory,
		\App\CoreModule\Controls\Skills\ISkillsControlFactory $skillsControlFactory,
		\App\CoreModule\Controls\Interests\IInterestsControlFactory $interestsControlFactory
	) {
		parent::__construct();
		$this->configService = $configService;
		$this->headerControlFactory = $headerControlFactory;
		$this->aboutControlFactory = $aboutControlFactory;
		$this->vCardControlFactory = $vCardControlFactory;
		$this->skillsControlFactory = $skillsControlFactory;
		$this->interestsControlFactory = $interestsControlFactory;
	}


	protected function createComponentHeader(): \App\CoreModule\Controls\Header\HeaderControl
	{
		return $this->headerControlFactory->create($this->configService->getConfigByKey('header', 'homepage'));
	}


	protected function createComponentAbout(): \App\CoreModule\Controls\About\AboutControl
	{
		return $this->aboutControlFactory->create();
	}


	protected function createComponentVCard(): \App\CoreModule\Controls\VCard\VCardControl
	{
		return $this->vCardControlFactory->create();
	}


	protected function createComponentSkills(): \App\CoreModule\Controls\Skills\SkillsControl
	{
		return $this->skillsControlFactory->create();
	}


	protected function createComponentInterests(): \App\CoreModule\Controls\Interests\InterestsControl
	{
		return $this->interestsControlFactory->create();
	}
}
