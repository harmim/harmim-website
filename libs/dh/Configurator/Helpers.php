<?php

declare(strict_types=1);

namespace Dh\Configurator;

final class Helpers
{
	use \Nette\StaticClass;

	public static function detectProductionDomain(): bool
	{
		if (\PHP_SAPI === 'cli') {
			return false;
		}

		$host = $_SERVER['HTTP_HOST'];
		if (\preg_match('~.*\.localhost\..*~', $host)) {
			return false;
		}

		return true;
	}


	public static function detectDebugMode(array $allowedIpAddresses = []): bool
	{
		if (\PHP_SAPI === 'cli') {
			return true;
		}

		if (
			!isset($_COOKIE[\Dh\Configurator\Configurator::SECRET_DEBUG_NAME])
			|| !(bool) $_COOKIE[\Dh\Configurator\Configurator::SECRET_DEBUG_NAME]
		) {
			return false;
		}

		if (\getenv(\Dh\Configurator\Configurator::SECRET_DEBUG_NAME) !== false) {
			return (bool) \getenv(\Dh\Configurator\Configurator::SECRET_DEBUG_NAME);

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
						return true;
				}
			}
		}

		return false;
	}
}
