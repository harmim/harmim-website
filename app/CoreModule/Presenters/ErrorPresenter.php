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

	/**
	 * @var \Nette\Http\IRequest
	 */
	private $httpRequest;


	public function __construct(\Tracy\ILogger $logger, \Nette\Http\IRequest $httpRequest)
	{
		$this->logger = $logger;
		$this->httpRequest = $httpRequest;
	}


	public function run(\Nette\Application\Request $request): \Nette\Application\IResponse
	{
		$exception = $request->getParameter('exception');
		$locale = $this->getLang();

		if ($exception instanceof \Nette\Application\BadRequestException) {
			$request->setParameters($request->getParameters() + [
				'locale' => $locale,
			]);
			$request->setPresenterName('Core:Error4xx');
			return new \Nette\Application\Responses\ForwardResponse($request);
		}

		$this->logger->log($exception, \Tracy\ILogger::EXCEPTION);

		return new \Nette\Application\Responses\CallbackResponse(function (
			\Nette\Http\IRequest $httpRequest,
			\Nette\Http\IResponse $httpResponse
		) use ($locale): void {
			if (\preg_match('~^text/html(?:;|$)~', (string) $httpResponse->getHeader('Content-Type'))) {
				require __DIR__ . '/../templates/Error500/default.phtml';
			}
		});
	}


	private function getLang(): string
	{
		if (\preg_match('~^\/(cs|en)\/?~', $this->httpRequest->getUrl()->getPath(), $matches)) {
			return $matches[1];
		}

		return 'cs';
	}
}
