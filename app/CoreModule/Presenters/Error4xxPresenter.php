<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

final class Error4xxPresenter extends \App\CoreModule\Presenters\BasePresenter
{
	/**
	 * @var \Tracy\ILogger
	 */
	private $logger;

	/**
	 * @var \Dh\Config\IConfigService
	 */
	private $configService;


	public function __construct(\Tracy\ILogger $logger, \Dh\Config\IConfigService $configService)
	{
		parent::__construct();
		$this->logger = $logger;
		$this->configService = $configService;
	}


	/**
	 * @throws \Nette\Application\BadRequestException
	 */
	protected function startup(): void
	{
		parent::startup();

		$request = $this->getRequest();
		if ($request && !$request->isMethod(\Nette\Application\Request::FORWARD)) {
			$this->error(
				\sprintf('Presenter %s should be used only in FORWARD request method.', self::class),
				\Nette\Http\IResponse::S500_INTERNAL_SERVER_ERROR
			);
		}
	}


	public function actionDefault(\Nette\Application\BadRequestException $exception): void
	{
		$this->logger->log(\sprintf(
			'HTTP code %s: %s in %s:%s.',
			$exception->getHttpCode(),
			$exception->getMessage(),
			$exception->getFile(),
			$exception->getLine()
		), \Tracy\ILogger::INFO);
	}


	public function renderDefault(\Nette\Application\BadRequestException $exception): void
	{
		$this->getTemplate()->add('code', $exception->getCode());
		$this->getTemplate()->add('domainUrl', $this->configService->getConfigByKey('domainUrl'));
	}
}
