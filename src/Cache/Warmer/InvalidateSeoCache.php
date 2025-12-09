<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 19:18
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file InvalidateSeoCache.php
 * @date 30/05/2025
 * @time 19:00
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Cache\Warmer;

use Idm\Bundle\Seo\Cache\CacheTagEnum;
use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final readonly class InvalidateSeoCache implements CacheWarmerInterface
{
	public function __construct (private CacheItemPoolInterface&TagAwareCacheInterface $cache) {}

	public function isOptional (): bool
	{
		return false;
	}

	/**
	 * @throws InvalidArgumentException
	 * @throws CacheException
	 */
	public function warmUp (string $cacheDir, ?string $buildDir = null): array
	{
		// Invalidate all cache of sitemap and seo
		$this->cache->invalidateTags([CacheTagEnum::ROUTES_LIST->value, CacheTagEnum::SITEMAP->value]);

		// Return the classes that have been loaded/prewarmed
		return [];
	}
}
