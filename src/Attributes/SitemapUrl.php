<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/06/2025, 21:21
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapUrl.php
 * @date    28/05/2025
 * @time    19:53
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Attributes;

use Attribute;
use DateMalformedStringException;
use DateTimeInterface;
use Idm\Bundle\Seo\Sitemap\Url;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final class SitemapUrl implements SitemapInterface
{
	public function __construct (
		public readonly string                        $priority = '0.5',
		public readonly string                        $changefreq = self::CHANGEFREQ_WEEKLY,
		public readonly null|string|DateTimeInterface $lastmod = null,
	) {}

	/**
	 * @throws DateMalformedStringException
	 */
	public function getUrl (string $loc): Url
	{
		return new Url($loc, $this->lastmod, $this->changefreq, $this->priority);
	}
}
