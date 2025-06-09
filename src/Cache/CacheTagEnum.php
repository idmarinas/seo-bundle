<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 08/06/2025, 22:53
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    CacheTagEnum.php
 * @date    08/06/2025
 * @time    20:57
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Cache;

enum CacheTagEnum: string
{
	case SITEMAP       = 'idm_seo_sitemap';
	case SITEMAP_INDEX = 'idm_seo_sitemap_index';
	case SITEMAP_PAGE  = 'idm_seo_sitemap_page';
	case SITEMAP_INFO  = 'idm_seo_sitemap_info';

	public function suffix (string $suffix = ''): string
	{
		return $this->name . $suffix;
	}
}
