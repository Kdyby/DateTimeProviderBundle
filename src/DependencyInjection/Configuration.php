<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types=1);

namespace Kdyby\DateTimeProviderBundle\DependencyInjection;

use Kdyby\StrictObjects\Scream;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use function method_exists;

class Configuration implements ConfigurationInterface
{
    use Scream;

    public const TYPE_CURRENT              = 'current_time';
    public const TYPE_REQUEST_TIME         = 'request_time';
    public const TYPE_MUTABLE_REQUEST_TIME = 'mutable_request_time';
    private const TYPES                    = [self::TYPE_CURRENT, self::TYPE_REQUEST_TIME, self::TYPE_MUTABLE_REQUEST_TIME];

    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kdyby_datetime_provider');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('kdyby_datetime_provider');
        }

		// @codingStandardsIgnoreStart
		$rootNode
			->children()
				->scalarNode('type')
					->defaultValue(self::TYPE_CURRENT)
					->validate()
						->ifNull()
						->ifNotInArray(self::TYPES)
							->thenInvalid('Type should be one of: ' . implode(', ', self::TYPES) . '.')
						->end()
					->end()
				->end()
			->end();
		// @codingStandardsIgnoreEnd

        return $treeBuilder;
    }
}
