<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Interests;

interface IInterestsControlFactory
{
	function create(): \App\CoreModule\Controls\Interests\InterestsControl;
}
