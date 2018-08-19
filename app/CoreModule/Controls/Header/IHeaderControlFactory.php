<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Header;

interface IHeaderControlFactory
{
	function create(array $links): \App\CoreModule\Controls\Header\HeaderControl;
}
