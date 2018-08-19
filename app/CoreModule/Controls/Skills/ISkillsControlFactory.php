<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Skills;

interface ISkillsControlFactory
{
	function create(): \App\CoreModule\Controls\Skills\SkillsControl;
}
