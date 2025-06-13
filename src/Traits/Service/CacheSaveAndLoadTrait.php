<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 13/06/2025, 16:58
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    CacheSaveAndLoadTrait.php
 * @date    04/06/2025
 * @time    19:48
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Service;

use Idm\Bundle\Seo\Cache\CacheKeyEnum;
use Idm\Bundle\Seo\Cache\CacheTagEnum;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;

trait CacheSaveAndLoadTrait
{
	/**
	 * @throws InvalidArgumentException
	 */
	private function getCachedSitemap (string $name, bool $invalidate = false): SitemapFile
	{
		return $this->cache->get(CacheKeyEnum::SITEMAP->suffix($name), function (ItemInterface $item) use ($name) {
			$item->tag(CacheTagEnum::SITEMAP->value);

			return new SitemapFile($name);
		}, $invalidate ? INF : null);
	}

	/**
	 * @throws CacheException
	 * @throws InvalidArgumentException
	 */
	private function save (string $name, SitemapFile $sitemap): void
	{
		$item = $this->cache->getItem(CacheKeyEnum::SITEMAP->suffix($name));

		$item
			->tag($this->tagCacheItem($name, $sitemap->isIndex()))
			->set($sitemap)
		;

		$this->cache->save($item);
	}

	/**
	 * @throws CacheException
	 * @throws InvalidArgumentException
	 */
	private function prepareToSave (string $name, SitemapFile $sitemap): void
	{
		if ($sitemap->isValid()) {
			$this->save($name, $sitemap);

			return;
		}

		$newDocuments = $sitemap->optimize('generateUrl');
		foreach ($newDocuments as $fileName => $document) {
			$this->save($fileName, $document);
		}
	}

	/** @private */
	private function tagCacheItem (string $name, bool $index): iterable
	{
		yield CacheTagEnum::SITEMAP->suffix($name);

		if ($index) {
			yield CacheTagEnum::SITEMAP_INDEX->name;
			yield CacheTagEnum::SITEMAP_INDEX->suffix($name);
		} else {
			yield CacheTagEnum::SITEMAP_PAGE->name;
			yield CacheTagEnum::SITEMAP_PAGE->suffix($name);
		}
	}
}
