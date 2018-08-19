<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\About;

interface IAboutControlFactory
{
	function create(): \App\CoreModule\Controls\About\AboutControl;
}
