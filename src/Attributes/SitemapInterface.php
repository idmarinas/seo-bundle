<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/06/2025, 21:21
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

namespace Idm\Bundle\Seo\Attributes;

interface SitemapInterface
{
	public const CHANGEFREQ_ALWAYS  = 'always';
	public const CHANGEFREQ_HOURLY  = 'hourly';
	public const CHANGEFREQ_DAILY   = 'daily';
	public const CHANGEFREQ_WEEKLY  = 'weekly';
	public const CHANGEFREQ_MONTHLY = 'monthly';
	public const CHANGEFREQ_YEARLY  = 'yearly';
	public const CHANGEFREQ_NEVER   = 'never';
}
