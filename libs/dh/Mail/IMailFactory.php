<?php

declare(strict_types=1);

namespace Dh\Mail;

interface IMailFactory
{
	function createByType(string $type, array $params = []): \Dh\Mail\Mail;
}
