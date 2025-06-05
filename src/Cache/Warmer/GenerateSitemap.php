<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/06/2025, 19:58
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    GenerateSitemap.php
 * @date    30/05/2025
 * @time    19:00
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Cache\Warmer;

use DateMalformedStringException;
use DOMException;
use Idm\Bundle\Seo\Service\SitemapGenerator;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

final readonly class GenerateSitemap implements CacheWarmerInterface
{
	public function __construct (private SitemapGenerator $generator) {}

	public function isOptional (): bool
	{
		return false;
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws InvalidArgumentException
	 * @throws DOMException
	 * @throws CacheException
	 */
	public function warmUp (string $cacheDir, ?string $buildDir = null): array
	{
		// Genera el sitemap
		$this->generator->generate();

		// Devuelve las clases que han sido cargadas/precalentadas
		return [];
	}
}
