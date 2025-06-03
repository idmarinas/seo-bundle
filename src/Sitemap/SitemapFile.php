<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/06/2025, 18:36
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapFile.php
 * @date    28/05/2025
 * @time    19:52
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap;

use DOMException;
use Psr\Cache\InvalidArgumentException;

final class SitemapFile extends AbstractSitemap
{
	/**
	 * @throws DOMException
	 * @throws InvalidArgumentException
	 */
	public function __construct (private readonly string $name)
	{
		parent::__construct(name: $this->name);

		$element = $this->getSitemap()->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');

		$this->getSitemap()->append($element);
	}
}
