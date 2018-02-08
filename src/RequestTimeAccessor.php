<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Kdyby\DateTimeProviderBundle;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestTimeAccessor
{

	use \Kdyby\StrictObjects\Scream;

	/** @var \Symfony\Component\HttpFoundation\RequestStack */
	private $requestStack;

	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}

	public function getRequestTime(): DateTimeImmutable
	{
		$request = $this->requestStack->getCurrentRequest();
		$time = $request !== NULL ? $request->server->get('REQUEST_TIME_FLOAT') : $_SERVER['REQUEST_TIME_FLOAT'];
		return (new DateTimeImmutable(sprintf('@%.6f', $time)))->setTimezone(new DateTimeZone(date_default_timezone_get()));
	}

}
