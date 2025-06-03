<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/06/2025, 22:35
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapIndex.php
 * @date    02/06/2025
 * @time    17:46
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap;

use DOMException;
use Psr\Cache\InvalidArgumentException;

final class SitemapIndex extends AbstractSitemap
{
	/**
	 * @throws DOMException
	 * @throws InvalidArgumentException
	 */
	public function __construct ()
	{
		parent::__construct('index');

		$element = $this->getSitemap()->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex');

		$this->getSitemap()->append($element);
	}
}
