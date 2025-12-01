<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 01/12/2025, 14:06
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapInterface.php
 * @date    29/05/2025
 * @time    18:43
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Attributes\Sitemap;

use Idm\Bundle\Seo\Sitemap\Node\Url;

interface SitemapInterface
{
	public const string CHANGEFREQ_ALWAYS  = 'always';
	public const string CHANGEFREQ_HOURLY  = 'hourly';
	public const string CHANGEFREQ_DAILY   = 'daily';
	public const string CHANGEFREQ_WEEKLY  = 'weekly';
	public const string CHANGEFREQ_MONTHLY = 'monthly';
	public const string CHANGEFREQ_YEARLY  = 'yearly';
	public const string CHANGEFREQ_NEVER   = 'never';

	public const array CHANGEFREQ_VALUES = [
		self::CHANGEFREQ_ALWAYS,
		self::CHANGEFREQ_HOURLY,
		self::CHANGEFREQ_DAILY,
		self::CHANGEFREQ_WEEKLY,
		self::CHANGEFREQ_MONTHLY,
		self::CHANGEFREQ_YEARLY,
		self::CHANGEFREQ_NEVER,
		null,
	];

	public function getUrl (string $loc): Url;
}
