<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/06/2025, 17:45
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapIndex.php
 * @date    02/06/2025
 * @time    17:46
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap;

use DateTimeInterface;
use DOMElement;
use DOMException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class SitemapIndex extends AbstractSitemap
{
	/**
	 * @throws DOMException
	 * @throws InvalidArgumentException
	 */
	public function __construct (private readonly CacheItemPoolInterface&TagAwareCacheInterface $cache)
	{
		parent::__construct('index', cache: $this->cache, index: true);

		$element = $this->getSitemap()->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex');

		$this->getSitemap()->append($element);
	}

	/**
	 * @param string                 $loc     URL location of the getSitemap file
	 * @param null|DateTimeInterface $lastmod Last modification date of the getSitemap
	 *
	 * @throws DOMException
	 */
	public function addSitemapNode (string $loc, ?DateTimeInterface $lastmod = null): void
	{
		$sitemap = $this->getSitemap()->createElement('sitemap');

		$sitemap->appendChild(new DOMElement('loc', $loc));
		if (null !== $lastmod) {
			$sitemap->appendChild(new DOMElement('lastmod', $lastmod->format(DateTimeInterface::W3C)));
		}

		$this->getSitemap()->appendChild($sitemap);
	}
}
