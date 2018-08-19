<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Header\Homepage;

interface IHomepageHeaderControlFactory
{
	function create(): \App\CoreModule\Controls\Header\HeaderControl;
}
