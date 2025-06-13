<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 12/06/2025, 22:09
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapControllerTest.php
 * @date    11/06/2025
 * @time    13:42
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SitemapControllerTest extends WebTestCase
{
	/**
	 * Prueba que la ruta del índice del sitemap funciona
	 */
	public function testSitemapIndex (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap.xml');

		$this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertEquals('text/xml; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
		$this->assertStringContainsString('<sitemapindex', $client->getResponse()->getContent());
	}

	/**
	 * Prueba que la ruta de un sitemap específico funciona
	 */
	public function testNotFoundSitemapFile (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap/pages.xml');

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
		$this->assertEquals('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
	}

	/**
	 * Prueba que la ruta de una página específica de un sitemap funciona
	 */
	public function testNotFoundSitemapFilePage (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap/pages.1.xml');

		$this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
		$this->assertEquals('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
	}

	/**
	 * Prueba que se manejan correctamente los formatos incorrectos
	 */
	public function testInvalidFormat (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap.html');

		$this->assertNotEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}

	/**
	 * Prueba que se manejan correctamente los nombres de sitemap inválidos
	 */
	public function testInvalidSitemapName (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap/123.xml');

		$this->assertNotEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
	}
}
