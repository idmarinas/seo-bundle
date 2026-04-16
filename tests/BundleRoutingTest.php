<?php

declare(strict_types=1);

/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 11/06/2025, 13:38
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    BundleRoutingTest.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */
namespace Idm\Bundle\Seo\Tests;

use Symfony\Component\Routing\Route;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;

final class BundleRoutingTest extends KernelTestCase
{
	use CreateKernelCaseTrait;

	public function testAddRoutingFile (): void
	{
		$kernel = self::bootKernel();

		$container = $kernel->getContainer();
		$container = $container->get('test.service_container');
		/**
		 * @var RouterInterface $router
		 */
		$router = $container->get(RouterInterface::class);
		$routeCollection = $router->getRouteCollection();
		$routes = $routeCollection->all();

		$this->assertCount(30, $routes);
		$this->assertInstanceOf(Route::class, $routeCollection->get('idm_seo_sitemap_index'));
		$this->assertInstanceOf(Route::class, $routeCollection->get('idm_seo_sitemap_file'));
		$this->assertInstanceOf(Route::class, $routeCollection->get('idm_seo_sitemap_file_page'));
	}
}
