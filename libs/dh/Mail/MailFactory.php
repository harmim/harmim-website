<?php

declare(strict_types=1);

namespace Dh\Mail;

final class MailFactory implements \Dh\Mail\IMailFactory
{
	use \Nette\SmartObject;

	/**
	 * @var \Dh\Config\IConfigService
	 */
	private $configService;

	/**
	 * @var \Nette\Mail\IMailer
	 */
	private $mailer;

	/**
	 * @var \Nette\Application\UI\ITemplateFactory
	 */
	private $templateFactory;

	/**
	 * @var \Kdyby\Translation\ITranslator
	 */
	private $translator;

	/**
	 * @var \Nette\Application\LinkGenerator
	 */
	private $linkGenerator;


	public function __construct(
		\Dh\Config\IConfigService $configService,
		\Nette\Mail\IMailer $mailer,
		\Nette\Application\UI\ITemplateFactory $templateFactory,
		\Kdyby\Translation\ITranslator $translator,
		\Nette\Application\LinkGenerator $linkGenerator
	) {
		$this->configService = $configService;
		$this->mailer = $mailer;
		$this->templateFactory = $templateFactory;
		$this->translator = $translator;
		$this->linkGenerator = $linkGenerator;
	}


	/**
	 * @throws \InvalidArgumentException
	 */
	public function createByType(string $type, array $params = []): \Dh\Mail\Mail
	{
		if (!\class_exists($type)) {
			throw new \InvalidArgumentException("Email of type $type does not exist.");
		}

		$mail = new $type(
			$this->configService,
			$this->mailer,
			$this->templateFactory,
			$this->translator,
			$this->linkGenerator,
			$params
		);

		if (!$mail instanceof \Dh\Mail\Mail) {
			throw new \InvalidArgumentException(\sprintf('Email must extends %s.', \Dh\Mail\Mail::class));
		}

		return $mail;
	}
}
