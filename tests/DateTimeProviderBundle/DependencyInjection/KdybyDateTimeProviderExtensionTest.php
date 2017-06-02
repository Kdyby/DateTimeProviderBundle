<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types = 1);

namespace KdybyTests\DateTimeProviderBundle\DependencyInjection;

use DateTimeImmutable;
use Kdyby\DateTimeProvider\DateProviderInterface;
use Kdyby\DateTimeProvider\DateTimeProviderInterface;
use Kdyby\DateTimeProvider\Provider\ConstantProvider;
use Kdyby\DateTimeProvider\Provider\CurrentProvider;
use Kdyby\DateTimeProvider\Provider\MutableProvider;
use Kdyby\DateTimeProvider\TimeProviderInterface;
use Kdyby\DateTimeProvider\TimeZoneProviderInterface;
use Kdyby\DateTimeProviderBundle\DependencyInjection\KdybyDateTimeProviderExtension;
use Kdyby\DateTimeProviderBundle\RequestTimeAccessor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class KdybyDateTimeProviderExtensionTest extends \PHPUnit\Framework\TestCase
{

	use \Kdyby\StrictObjects\Scream;

	/** @var \Kdyby\DateTimeProviderBundle\DependencyInjection\KdybyDateTimeProviderExtension */
	private $extension;

	/** @var \Symfony\Component\DependencyInjection\ContainerBuilder */
	private $container;

	protected function setUp(): void
	{
		$this->extension = new KdybyDateTimeProviderExtension();

		$this->container = new ContainerBuilder();
		$this->container->registerExtension($this->extension);
		$this->container->setDefinition(
			RequestTimeAccessor::class,
			(new Definition(RequestTimeAccessor::class))->setSynthetic(TRUE)
		);
	}

	public function testDefaults(): void
	{
		$this->container->loadFromExtension($this->extension->getAlias());
		$this->loadConfiguration('defaultValues');
		$this->container->compile();

		$this->assertInstanceOf(CurrentProvider::class, $this->getProvider());
	}

	/**
	 * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
	 */
	public function testInvalidType(): void
	{
		$this->container->loadFromExtension($this->extension->getAlias());
		$this->loadConfiguration('invalidType');
		$this->container->compile();
	}

	/**
	 * @dataProvider interfacesProvider
	 */
	public function testInterfaces(string $interface): void
	{
		$this->container->loadFromExtension($this->extension->getAlias());
		$this->container->compile();

		$this->assertInstanceOf($interface, $this->getProvider());
	}

	public function testRequestTimeType(): void
	{
		$this->container->loadFromExtension($this->extension->getAlias());
		$this->loadConfiguration('requestTimeType');
		$this->container->compile();

		$dateTime = new DateTimeImmutable();
		$this->container->set(RequestTimeAccessor::class, $this->createRequestTimeAccessorMock($dateTime));

		$this->assertInstanceOf(ConstantProvider::class, $this->getProvider());
		$this->assertSame($dateTime, $this->getProvider()->getDateTime());

		sleep(1);

		$this->assertSame($dateTime, $this->getProvider()->getDateTime());
	}

	public function testMutableRequestTimeType(): void
	{
		$this->container->loadFromExtension($this->extension->getAlias());
		$this->loadConfiguration('mutableRequestTimeType');
		$this->container->compile();

		$dateTime = new DateTimeImmutable();
		$this->container->set(RequestTimeAccessor::class, $this->createRequestTimeAccessorMock($dateTime));

		$this->assertInstanceOf(MutableProvider::class, $this->getProvider());
		$this->assertSame($dateTime, $this->getProvider()->getDateTime());

		$newDateTime = new DateTimeImmutable();
		$this->getProvider()->changePrototype($newDateTime);

		$this->assertSame($newDateTime, $this->getProvider()->getDateTime());

		sleep(1);

		$this->assertSame($newDateTime, $this->getProvider()->getDateTime());
	}

	private function loadConfiguration(string $fixtureName): void
	{
		$loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../fixtures/'));
		$loader->load($fixtureName . '.yaml');
	}

	/**
	 * @return \Kdyby\DateTimeProvider\Provider\ConstantProvider|\Kdyby\DateTimeProvider\Provider\CurrentProvider|\Kdyby\DateTimeProvider\Provider\MutableProvider
	 */
	private function getProvider()
	{
		return $this->container->get(KdybyDateTimeProviderExtension::SERVICE_NAME);
	}

	private function createRequestTimeAccessorMock(DateTimeImmutable $dateTime): RequestTimeAccessor
	{
		return new class ($dateTime) extends \Kdyby\DateTimeProviderBundle\RequestTimeAccessor {
			/** @var \DateTimeImmutable */
			private $dateTime;

			public function __construct(DateTimeImmutable $dateTime)
			{
				$this->dateTime = $dateTime;
			}

			public function getRequestTime(): DateTimeImmutable
			{
				return $this->dateTime;
			}

		};
	}

	/**
	 * @return string[][]
	 */
	public function interfacesProvider(): array
	{
		return [
			[DateTimeProviderInterface::class],
			[DateProviderInterface::class],
			[TimeProviderInterface::class],
			[TimeZoneProviderInterface::class],
		];
	}

}
