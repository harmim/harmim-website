<?php

declare(strict_types=1);

namespace Dh\Forms;

interface IFormFactory
{
	function create(\Nette\ComponentModel\IContainer $parent = null, string $name = null): \Nette\Application\UI\Form;
}
