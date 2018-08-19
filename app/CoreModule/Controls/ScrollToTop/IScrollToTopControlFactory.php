<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\ScrollToTop;

interface IScrollToTopControlFactory
{
	function create(): \App\CoreModule\Controls\ScrollToTop\ScrollToTopControl;
}
