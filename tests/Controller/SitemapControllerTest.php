<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 13/06/2025, 17:36
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

use Override;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class SitemapControllerTest extends WebTestCase
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	protected static function createKernel (array $options = []): KernelInterface
	{
		return parent::createKernel(array_merge($options, ['environment' => 'sitemap']));
	}

	/**
	 * Prueba que la ruta del índice del sitemap funciona
	 */
	public function testSitemapIndex (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap.xml');

		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
		$this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
		$this->assertStringContainsString('<sitemapindex', $client->getResponse()->getContent());
	}

	/**
	 * Prueba que la ruta de un sitemap específico funciona
	 */
	public function testNotFoundSitemapFile (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap/pages.xml');

		$this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
		$this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
		$this->assertPageTitleContains('An error occurred');
		$this->assertSelectorTextContains('detail', 'Sitemap "pages.xml" not found.');
	}

	/**
	 * Prueba que la ruta de una página específica de un sitemap funciona
	 */
	public function testNotFoundSitemapFilePage (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap/pages.1.xml');

		$this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
		$this->assertPageTitleContains('An error occurred');
		$this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
		$this->assertSelectorTextContains('detail', 'Sitemap "pages.1.xml" not found.');
	}

	/**
	 * Prueba que se manejan correctamente los formatos incorrectos
	 */
	public function testInvalidFormat (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap.html');

		$this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
		$this->assertPageTitleContains('No route found for "GET http://localhost/sitemap.html"');
	}

	/**
	 * Prueba que se manejan correctamente los nombres de sitemap inválidos
	 */
	public function testInvalidSitemapName (): void
	{
		$client = static::createClient();
		$client->request('GET', '/sitemap/123.xml');

		$this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
		$this->assertPageTitleContains('No route found for "GET http://localhost/sitemap/123.xml"');
	}
}
