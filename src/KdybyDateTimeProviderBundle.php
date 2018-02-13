<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types=1);

namespace Kdyby\DateTimeProviderBundle;

use Kdyby\StrictObjects\Scream;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KdybyDateTimeProviderBundle extends Bundle
{
    use Scream;

    public function getContainerExtension() : ExtensionInterface
    {
        if ($this->extension === null) {
            $this->extension = $this->createContainerExtension();
        }

        return $this->extension;
    }
}
