<?php

declare(strict_types=1);

namespace Dh\Mail;

final class LogMailer implements \Nette\Mail\IMailer
{
	use \Nette\SmartObject;

	/**
	 * @var string
	 */
	private $logDir;


	public function __construct(string $logDir)
	{
		$this->logDir = $logDir;
	}


	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public function send(\Nette\Mail\Message $mail): void
	{
		$fileName = "$this->logDir/mails/mail-"
			. \date('Y-m-d-H-i-s')
			. '_'
			. \Nette\Utils\Random::generate(4)
			. '.eml';
		\Nette\Utils\FileSystem::write($fileName, $mail->generateMessage());
	}
}
