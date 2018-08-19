<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\VCard;

interface IVCardControlFactory
{
	function create(): \App\CoreModule\Controls\VCard\VCardControl;
}
