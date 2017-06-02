<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types = 1);

namespace KdybyTests\DateTimeProviderBundle;

use Kdyby\DateTimeProviderBundle\RequestTimeAccessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestTimeAccessorTest extends \PHPUnit\Framework\TestCase
{

	use \Kdyby\StrictObjects\Scream;

	/** @var \Symfony\Component\HttpFoundation\RequestStack */
	private $requestStack;

	/** @var \Kdyby\DateTimeProviderBundle\RequestTimeAccessor */
	private $accessor;

	protected function setUp(): void
	{
		$this->requestStack = new RequestStack();
		$this->accessor = new RequestTimeAccessor($this->requestStack);
	}

	public function test(): void
	{
		$_SERVER['REQUEST_TIME_FLOAT'] = 123456789.123456;
		$this->requestStack->push(Request::createFromGlobals());

		$this->assertSame('123456789.123456', $this->accessor->getRequestTime()->format('U.u'));
	}

}
