<?php

declare(strict_types=1);

namespace Dh\Config;

final class ConfigService implements \Dh\Config\IConfigService
{
	use \Nette\SmartObject;

	/**
	 * @var array
	 */
	private $config;


	public function __construct(array $config)
	{
		$this->config = $config;
	}


	public function getConfig(): array
	{
		return $this->config;
	}


	/**
	 * @return mixed
	 */
	public function getConfigByKey(string ...$keys)
	{
		if (!$keys) {
			return null;
		}

		$config = $this->config;
		foreach ($keys as $key) {
			if (!\is_array($config) || !\array_key_exists($key, $config)) {
				return null;
			}
			$config = $config[$key];
		}

		return $config;
	}
}
