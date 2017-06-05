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

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class KdybyDateTimeProviderBundle extends \Symfony\Component\HttpKernel\Bundle\Bundle
{

	use \Kdyby\StrictObjects\Scream;

	public function getContainerExtension(): ExtensionInterface
	{
		if ($this->extension === NULL) {
			$this->extension = $this->createContainerExtension();
		}

		return $this->extension;
	}

}
