<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\PreLoader;

interface IPreLoaderControlFactory
{
	function create(): \App\CoreModule\Controls\PreLoader\PreLoaderControl;
}
