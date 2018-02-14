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

use Kdyby\DateTimeProvider\Provider\ConstantProvider;
use Kdyby\DateTimeProvider\Provider\CurrentProvider;
use Kdyby\DateTimeProvider\Provider\MutableProvider;
use Kdyby\StrictObjects\Scream;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use function assert;

class KdybyDateTimeProviderExtension extends Extension
{
    use Scream;

    public const SERVICE_NAME = 'kdyby.datetime_provider.provider';

    public function getAlias() : string
    {
        return 'kdyby_datetime_provider';
    }

    /**
     * @param mixed[][] $configs
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $this->resolveProvider($config, $container);
    }

    /**
     * @param mixed[] $config
     */
    private function resolveProvider(array $config, ContainerBuilder $container) : void
    {
        switch ($config['type']) {
            case Configuration::TYPE_CURRENT:
                $this->registerAlias($container, CurrentProvider::class);
                return;
            case Configuration::TYPE_REQUEST_TIME:
                $this->registerAlias($container, ConstantProvider::class);

                return;
            case Configuration::TYPE_MUTABLE_REQUEST_TIME:
                $this->registerAlias($container, MutableProvider::class);
                return;
        }

        assert(false);
    }

    private function registerAlias(ContainerBuilder $container, string $alias) : void
    {
        $container->setAlias(self::SERVICE_NAME, new Alias($alias, true));
    }
}
