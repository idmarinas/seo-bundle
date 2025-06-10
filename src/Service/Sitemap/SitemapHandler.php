<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/06/2025, 16:52
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

use Idm\Bundle\Seo\Cache\SitemapInfo;
use Idm\Bundle\Seo\Traits\Service\SaveLoadTrait;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class SitemapHandler
{
	use SaveLoadTrait;

	public function __construct (private readonly CacheItemPoolInterface&TagAwareCacheInterface $cache) {}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getRootSitemap (): SitemapInfo
	{
		$sitemap = $this->getCachedSitemap('index', true);

		return $sitemap;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getIndexSitemap (string $name): SitemapInfo
	{
		$sitemap = $this->getCachedSitemap($name, true);

		return $sitemap;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getSitemapPage (string $name, int $id): SitemapInfo
	{
		$sitemap = $this->getCachedSitemap($name . '.' . $id);

		return $sitemap;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getSitemap (string $name): SitemapInfo
	{
		$sitemap = $this->getCachedSitemap($name);

		return $sitemap;
	}
}
