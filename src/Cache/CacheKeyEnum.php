<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 08/06/2025, 22:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    CacheKeyEnum.php
 * @date    08/06/2025
 * @time    22:38
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Cache;

enum CacheKeyEnum: string
{
	case SITEMAP = 'idm_seo_sitemap';

	public function suffix (string $suffix = ''): string
	{
		return $this->name . '_' . $suffix;
	}
}
