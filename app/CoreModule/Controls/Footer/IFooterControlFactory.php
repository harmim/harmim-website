<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Footer;

interface IFooterControlFactory
{
	function create(): \App\CoreModule\Controls\Footer\FooterControl;
}
