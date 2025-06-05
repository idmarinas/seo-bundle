<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/06/2025, 19:06
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SaveLoadTrait.php
 * @date    04/06/2025
 * @time    19:48
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Service\SitemapGenerator;

use DateTime;
use Idm\Bundle\Seo\Cache\SitemapInfo;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;

trait SaveLoadTrait
{

	/**
	 * @throws InvalidArgumentException
	 */
	private function getCachedSitemap (string $name, bool $index = false): SitemapInfo
	{
		return $this->cache->get(self::getCacheKey($name), function (ItemInterface $item) use ($name, $index) {
			$item->tag(self::getCacheKey($name));

			return (new SitemapInfo(new DateTime(), new SitemapFile($name, $index)));
		});
	}

	/**
	 * @throws CacheException
	 * @throws InvalidArgumentException
	 */
	private function save (string $name, SitemapInfo $sitemap): void
	{
		$item = $this->cache->getItem(self::getCacheKey($name));
		$sitemap->setUpdatedAt(new DateTime());

		$item
			->tag(self::getCacheKey($name))
			->set($sitemap)
		;

		$this->cache->save($item);
	}
}
