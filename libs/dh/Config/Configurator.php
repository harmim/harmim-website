<?php

declare(strict_types=1);

namespace Dh\Config;

class Configurator extends \Nette\Configurator
{
	private const SECRET_DEBUG_NAME = 'DH_DEBUG';

	private const CONFIG_KEY_IS_PRODUCTION_DOMAIN = 'isProductionDomain';


	public function __construct()
	{
		parent::__construct();
		$this->parameters[self::CONFIG_KEY_IS_PRODUCTION_DOMAIN] = $this->detectProductionDomain();
	}


	public function dhDetectDebugMode(array $allowedIpAddresses = []): bool
	{
		if ($this->parameters['consoleMode']) {
			return true;
		}

		if (!isset($_COOKIE[self::SECRET_DEBUG_NAME])) {
			return false;
		}

		$debugMode = false;
		if (\getenv(self::SECRET_DEBUG_NAME) !== false) {
			$debugMode = (bool) \getenv(self::SECRET_DEBUG_NAME);

		} else {
			$ip = $_SERVER['REMOTE_ADDR'] ?? \php_uname('n');
			if (\filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4 | \FILTER_FLAG_IPV6) !== false) {
				if (!isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !isset($_SERVER['HTTP_FORWARDED'])) {
					$allowedIpAddresses[] = '127.0.0.1';
					$allowedIpAddresses[] = '::1';
				}

				switch (true) {
					case \filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE) === false:
					case \Nette\Http\Helpers::ipMatch($ip, '127.0.0.0/8'):
					case \in_array($ip, $allowedIpAddresses, true):
						$debugMode = true;
						break;
				}
			}
		}

		return $debugMode && (bool) $_COOKIE[self::SECRET_DEBUG_NAME];
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


	private function detectProductionDomain(): bool
	{
		if ($this->parameters['consoleMode']) {
			return false;
		}

		$host = $_SERVER['HTTP_HOST'];
		if (\preg_match('~.*\.localhost\..*~', $host)) {
			return false;
		}

		return true;
	}
}
