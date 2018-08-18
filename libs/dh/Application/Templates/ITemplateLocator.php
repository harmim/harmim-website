<?php

declare(strict_types=1);

namespace Dh\Application\Templates;

interface ITemplateLocator
{
	function formatViewTemplate(string $presenterName, string $view): array;

	function formatLayoutTemplate(string $presenterName, string $layout = ''): array;

	function formatControlTemplate(string $controlName, string $controlDir, ?string $templateName = null): array;
}
