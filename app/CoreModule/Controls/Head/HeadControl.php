<?php

declare(strict_types=1);

namespace App\CoreModule\Controls\Head;

final class HeadControl extends \Dh\Application\UI\BaseControl
{
	/**
	 * @var bool
	 */
	private $isProductionDomain;

	/**
	 * @var string
	 */
	private $wwwDir;


	public function __construct(
		bool $isProductionDomain,
		string $wwwDir
	) {
		parent::__construct();
		$this->isProductionDomain = $isProductionDomain;
		$this->wwwDir = $wwwDir;
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$scripts = \Nette\Utils\Strings::startsWith((string) $this->getTemplateName(), 'scripts');
		$this->getTemplate()->add($scripts ? 'scripts' : 'styles', $this->getFilesToInclude($scripts));
	}


	private function getFilesToInclude(bool $scripts): array
	{
		$files = [];
		$baseDir = $scripts ? 'js' : 'styles';
		$subdir = $this->isProductionDomain ? 'production' : 'development';
		$path = "$this->wwwDir/$baseDir/$subdir";
		$ext = $scripts ? 'js' : 'css';
		if (\is_readable($path)) {
			/** @var \SplFileInfo $file */
			foreach (\Nette\Utils\Finder::findFiles("*.$ext")->in($path) as $file) {
				if ($file->isReadable()) {
					$fileName = $file->getFilename();
					$files[$fileName] = "/$baseDir/$subdir/$fileName";
				}
			}
		}
		\ksort($files);

		return $files;
	}
}
