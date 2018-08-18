<?php

declare(strict_types=1);

namespace Dh\Config;

final class Configurator extends \Nette\Configurator
{
	public const SECRET_DEBUG_NAME = 'DH_DEBUG';

	private const CONFIG_KEY_IS_PRODUCTION_DOMAIN = 'isProductionDomain';


	public function __construct(string $rootDir)
	{
		parent::__construct();
		$this->addParameters([
			'rootDir' => $rootDir,
			'appDir' => "$rootDir/app",
			'logDir' => "$rootDir/log",
			'tempDir' => "$rootDir/temp",
			'wwwDir' => "$rootDir/www",
			self::CONFIG_KEY_IS_PRODUCTION_DOMAIN => \Dh\Config\Helpers::detectProductionDomain(),
		]);
	}


	public function loadConfigs(
		array $configs = [],
		array $configDirs = [],
		string $productionConfig = 'production',
		string $developmentConfig = 'development'
	): self {
		if (!$configs) {
			$configs = ['config'];
		}
		if (!$configDirs) {
			$configDirs = [$this->parameters['appDir'] . '/config'];
		}

		\array_unshift(
			$configs,
			$this->parameters[self::CONFIG_KEY_IS_PRODUCTION_DOMAIN] ? $productionConfig : $developmentConfig
		);

		foreach ($configDirs as $configDir) {
			foreach ($configs as $config) {
				$path = "$configDir/$config.neon";
				if (\is_readable($path)) {
					$this->addConfig($path);
				}
			}
		}

		return $this;
	}
}
