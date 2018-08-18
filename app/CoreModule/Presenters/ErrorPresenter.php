<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

final class ErrorPresenter implements \Nette\Application\IPresenter
{
	use \Nette\SmartObject;

	/**
	 * @var \Tracy\ILogger
	 */
	private $logger;


	public function __construct(\Tracy\ILogger $logger)
	{
		$this->logger = $logger;
	}


	public function run(\Nette\Application\Request $request): \Nette\Application\IResponse
	{
		$exception = $request->getParameter('exception');

		if ($exception instanceof \Nette\Application\BadRequestException) {
			return new \Nette\Application\Responses\ForwardResponse($request->setPresenterName('Core:Error4xx'));
		}

		$this->logger->log($exception, \Tracy\ILogger::EXCEPTION);

		return new \Nette\Application\Responses\CallbackResponse(function (
			\Nette\Http\IRequest $httpRequest,
			\Nette\Http\IResponse $httpResponse
		): void {
			if (\preg_match('~^text/html(?:;|$)~', (string) $httpResponse->getHeader('Content-Type'))) {
				if (\preg_match('~^\/([a-z]{2})\/~', $httpRequest->getUrl()->getPath(), $matches)) {
					$lang = $matches[1];
				}

				require __DIR__ . '/../templates/Error500/default.phtml';
			}
		});
	}
}
