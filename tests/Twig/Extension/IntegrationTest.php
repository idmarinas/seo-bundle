<?php
/**
 * Copyright 2021-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/11/2025, 16:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    IntegrationTest.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Tests\Twig\Extension;

use App\Kernel;
use Idm\Bundle\Seo\Service\SeoPage;
use Idm\Bundle\Seo\Twig\Extension\SeoExtension;
use Idm\Bundle\Seo\Twig\Runtime\SeoRuntime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\Test\IntegrationTestCase;

/**
 * Test Twig Extensions.
 */
final class IntegrationTest extends IntegrationTestCase
{
	public static function getFixturesDirectory (): string
	{
		return __DIR__ . '/Fixtures/';
	}

	public function getExtensions (): array
	{
		return [
			new SeoExtension(),
		];
	}

	protected function getRuntimeLoaders (): iterable
	{
		$container = $this->getContainer();
		/** @var SeoPage $seoPage */
		$seoPage = $container->get(SeoPage::class);

		yield new FactoryRuntimeLoader([
			SeoRuntime::class => fn(): SeoRuntime => new SeoRuntime($seoPage),
		]);
	}

	protected function getContainer (): ContainerInterface
	{
		$kernel = new Kernel('test', true);
//		$kernel->addExtraConfig(dirname(__DIR__, 2) . '/config/idm_advertising.php');
		$kernel->boot();

		return $kernel->getContainer();
	}
}
