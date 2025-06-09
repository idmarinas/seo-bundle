<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/06/2025, 21:29
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

namespace Idm\Bundle\Seo\Traits\Service;

use DateTime;
use Idm\Bundle\Seo\Cache\CacheKeyEnum;
use Idm\Bundle\Seo\Cache\CacheTagEnum;
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
	private function getCachedSitemap (string $name, bool $index = false, bool $invalidate = false): SitemapInfo
	{
		return $this->cache->get(CacheKeyEnum::SITEMAP->suffix($name), function (ItemInterface $item) use ($name, $index) {
			$item->tag($this->tagCacheItem($name, $index));

			return (new SitemapInfo(new DateTime(), new SitemapFile($name, $index)));
		}, $invalidate ? INF : null);
	}

	/**
	 * @throws CacheException
	 * @throws InvalidArgumentException
	 */
	private function save (string $name, SitemapInfo $sitemap): void
	{
		$item = $this->cache->getItem(CacheKeyEnum::SITEMAP->suffix($name));
		$sitemap->setUpdatedAt(new DateTime());

		$item->set($sitemap);

		$this->cache->save($item);
	}

	/**
	 * @throws CacheException
	 * @throws InvalidArgumentException
	 */
	private function prepareToSave (string $name, SitemapInfo $sitemap): void
	{
		$document = $sitemap->getDocument();

		if ($document->isValid()) {
			$this->save($name, $sitemap);

			return;
		}

		$newDocuments = $document->optimize('generateUrl');
		foreach ($newDocuments as $fileName => $partDocument) {
			$sitemapInfo = new SitemapInfo(new DateTime(), $partDocument);

			$this->save($fileName, $sitemapInfo);
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
