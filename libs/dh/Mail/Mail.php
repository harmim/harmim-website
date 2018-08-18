<?php

declare(strict_types=1);

namespace Dh\Mail;

abstract class Mail
{
	use \Nette\SmartObject;

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var \Kdyby\Translation\ITranslator
	 */
	protected $translator;

	/**
	 * @var \Nette\Application\LinkGenerator
	 */
	protected $linkGenerator;

	/**
	 * @var \Nette\Mail\IMailer
	 */
	private $mailer;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @var \Nette\Mail\Message
	 */
	private $message;

	/**
	 * @var \Nette\Bridges\ApplicationLatte\Template
	 */
	private $template;

	/**
	 * @var string|null
	 */
	private $templateFile;


	public function __construct(
		array $config,
		\Nette\Mail\IMailer $mailer,
		\Nette\Application\UI\ITemplateFactory $templateFactory,
		\Kdyby\Translation\ITranslator $translator,
		\Nette\Application\LinkGenerator $linkGenerator,
		array $params = []
	) {
		$this->config = $config;
		$this->mailer = $mailer;
		$this->translator = $translator;
		$this->linkGenerator = $linkGenerator;
		$this->params = $params;

		$this->message = new \Nette\Mail\Message();

		$template = $templateFactory->createTemplate();
		if (!$template instanceof \Nette\Bridges\ApplicationLatte\Template) {
			throw new \Nette\InvalidStateException(\sprintf(
				'Class %s should returns instance of %s.',
				\get_class($templateFactory),
				\Nette\Bridges\ApplicationLatte\Template::class
			));
		}
		$this->template = $template;
		$this->template->getLatte()->addProvider('uiControl', $linkGenerator);

		$this->setDefaultParams();
		$this->create($this->message, $this->params);
	}


	/**
	 * @throws \ReflectionException
	 */
	public function send(): void
	{
		$this->setTemplateVariables();

		if (!$this->message->getBody() && !$this->message->getHtmlBody()) {
			$this->template->setFile($this->getTemplateFile());
			$this->message->setHtmlBody((string) $this->template);
		}

		$this->mailer->send($this->message);
	}


	public function setTemplateFile(?string $templateFile): void
	{
		$this->templateFile = $templateFile;
	}


	abstract protected function create(\Nette\Mail\Message $message, array $params = []): void;


	/**
	 * @throws \ReflectionException
	 */
	private function getTemplateFile(): string
	{
		if ($this->templateFile) {
			return $this->templateFile;
		}

		$classPath = (string) (new \ReflectionClass($this))->getFileName();
		$classDir = \dirname($classPath);
		$className = \pathinfo($classPath, \PATHINFO_FILENAME);
		$templateName = \lcfirst($className) . '.latte';
		$templateFile = "$classDir/templates/$templateName";

		if (!\is_readable($templateFile)) {
			throw new \Nette\FileNotFoundException("Template $templateFile not found.");
		}

		return $this->templateFile = $templateFile;
	}


	private function setDefaultParams(): void
	{
		$this->params['defaultFrom'] = $this->config['mail']['defaultFrom'] ?? null;
		$this->params['defaultFromName'] = $this->config['mail']['defaultFromName'] ?? null;
		$this->params['domainUrl'] = $this->config['domainUrl'] ?? null;
	}


	private function setTemplateVariables(): void
	{
		foreach ($this->params as $key => $value) {
			$this->template->add($key, $value);
		}
		$this->template->add('subject', $this->message->getSubject());
	}
}
