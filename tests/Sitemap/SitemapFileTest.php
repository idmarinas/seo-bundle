<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 15/06/2025, 21:52
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
		yield ['test', 'urlset', false, true, true];
		yield ['index', 'sitemapindex', true, true, true];
		yield ['page.index', 'sitemapindex', true, true, true];
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws Exception
	 */
	public function testSitemapIndexWithSitemaps (): void
	{
		$sitemap = new SitemapFile('index');

		$this->assertTrue($sitemap->isIndex());

		$this->assertEquals('index', $sitemap->getName());

		$this->assertTrue($sitemap->isEmpty());

		$this->assertTrue($sitemap->isValid());

		$sitemap->addSitemap(new Sitemap('https://www.example.com', new DateTime()));

		$this->assertFalse($sitemap->isEmpty());

		$this->assertCount(1, $sitemap);

		$sitemap->addSitemap(new Sitemap('https://www.example.com', new DateTime('yesterday')));

		$this->assertCount(1, $sitemap);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Cannot add URL node to index sitemap. Use addSitemap() instead.');
		$sitemap->addUrl(new Url('https://www.example.com', new DateTime()));
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws DOMException
	 */
	public function SitemapWithUrl (string $name, bool $index, bool $empty, bool $valid): void
	{
		$sitemap = new SitemapFile($name);

		$this->assertEquals($name, $sitemap->getName());

		$this->assertEquals($index, $sitemap->isIndex());

		$this->assertEquals($empty, $sitemap->isEmpty());

		$this->assertEquals($valid, $sitemap->isValid());

		$sitemap->addSitemap(new Sitemap('https://www.example.com', new DateTime()));

		$this->assertFalse($sitemap->isEmpty());

		$this->assertCount(1, $sitemap);

		$sitemap->addSitemap(new Sitemap('https://www.example.com', new DateTime('yesterday')));

		$this->assertCount(1, $sitemap);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Cannot add URL node to index sitemap. Use addSitemap() instead.');
		$sitemap->addUrl(new Url('https://www.example.com', new DateTime()));
	}

}
