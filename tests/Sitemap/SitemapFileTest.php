<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 16/06/2025, 15:16
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapFileTest.php
 * @date    12/06/2025
 * @time    18:40
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Tests\Sitemap;

use DateMalformedStringException;
use DateTime;
use DOMException;
use Exception;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Sitemap\Node\Url;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use PHPUnit\Framework\TestCase;

class SitemapFileTest extends TestCase
{
	/**
	 * @dataProvider initializationDataProvider
	 * @throws DOMException
	 */
	public function testSitemapInitialization (
		string $name,
		string $node,
		bool   $index,
		bool   $empty,
		bool   $valid
	): void {
		$sitemap = new SitemapFile($name);

		$this->assertEquals($name, $sitemap->getName());

		$this->assertEquals($index, $sitemap->isIndex());

		$this->assertEquals($empty, $sitemap->isEmpty());

		$this->assertEquals($valid, $sitemap->isValid());

		$xml = $sitemap->toString();
		$this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $xml);
		$this->assertStringContainsString(
			sprintf('<%s xmlns="https://www.sitemaps.org/schemas/sitemap/0.9"/>', $node),
			$xml
		);
	}

	public function initializationDataProvider (): iterable
	{
		yield 'test page urlset' => ['test', 'urlset', false, true, true];
		yield 'test index sitemap' => ['test.index', 'sitemapindex', true, true, true];
		yield 'page number 0' => ['test.0', 'urlset', false, true, true];
		yield 'page number 23' => ['test.23', 'urlset', false, true, true];
		yield 'index sitemap' => ['index', 'sitemapindex', true, true, true];
		yield 'page index sitemap' => ['page.index', 'sitemapindex', true, true, true];
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws Exception
	 */
	public function testSitemapIndex (): void
	{
		$sitemap = new SitemapFile('index');

		$this->assertTrue($sitemap->isIndex());
		$this->assertTrue($sitemap->isEmpty());

		$sitemap->addSitemap(new Sitemap('https://www.example.com/news.xml', new DateTime('2015-09-31')));

		$this->assertFalse($sitemap->isEmpty());
		$this->assertCount(1, $sitemap);

		$sitemap->addSitemap(new Sitemap('https://www.example.com/news.xml', new DateTime('2016-09-31')));

		$this->assertCount(1, $sitemap);

		$sitemap->addSitemap(new Sitemap('https://www.example.com/items.xml', new DateTime('2017-09-31')));

		$this->assertCount(2, $sitemap);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Cannot add URL node to index sitemap. Use addSitemap() instead.');
		$sitemap->addUrl(new Url('https://www.example.com/ex.xml', new DateTime()));

		$this->assertCount(2, $sitemap);
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws Exception
	 */
	public function testSitemapFile (): void
	{
		$sitemap = new SitemapFile('news');

		$this->assertFalse($sitemap->isIndex());
		$this->assertTrue($sitemap->isEmpty());

		$sitemap->addUrl(new Url('https://www.example.com', new DateTime('2015-09-31')));

		$this->assertFalse($sitemap->isEmpty());
		$this->assertCount(1, $sitemap);

		$sitemap->addUrl(new Url('https://www.example.com', new DateTime('2016-09-31')));

		$this->assertCount(1, $sitemap);

		$sitemap->addUrl(new Url('https://www.example.com/mapas/41', new DateTime('2017-09-31')));

		$this->assertCount(2, $sitemap);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Cannot add sitemap node to non-index sitemap. Use addUrl() instead.');
		$sitemap->addSitemap(new Sitemap('https://www.example.com', new DateTime()));

		$this->assertCount(2, $sitemap);
	}
}
