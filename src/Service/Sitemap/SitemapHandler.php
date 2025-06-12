<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 12/06/2025, 19:11
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapHandler.php
 * @date    05/06/2025
 * @time    18:45
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service\Sitemap;

use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Idm\Bundle\Seo\Traits\Service\SaveLoadTrait;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use function Symfony\Component\String\u;

final class SitemapHandler
{
	use SaveLoadTrait;

	public function __construct (private readonly CacheItemPoolInterface&TagAwareCacheInterface $cache) {}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getSitemap (string $name, ?int $id = null): SitemapFile
	{
		return match (true) {
			// Index sitemap.xml
			'index' === $name || 'root' === $name => $this->getCachedSitemap('index'),
			u($name)->endsWith('.index')          => $this->getCachedSitemap($name),
			null !== $id                          => $this->getCachedSitemap($name . '.' . $id),
		};
	}
}
