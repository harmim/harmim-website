<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Head;

interface IHeadControlFactory
{
	function create(): \App\CoreModule\Controls\Head\HeadControl;
}
