<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 04/06/2025, 21:32
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

namespace Idm\Bundle\Seo\Attributes\Sitemap;

use Attribute;
use DateMalformedStringException;
use Idm\Bundle\Seo\Sitemap\Node\Url;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final readonly class SitemapUrl implements SitemapInterface
{
	public function __construct (
		public string $priority = '0.5',
		public string $changefreq = self::CHANGEFREQ_WEEKLY,
	) {}

	/**
	 * @inheritdoc
	 * @throws DateMalformedStringException
	 */
	public function getUrl (string $loc): Url
	{
		return new Url($loc, null, $this->changefreq, $this->priority);
	}
}
