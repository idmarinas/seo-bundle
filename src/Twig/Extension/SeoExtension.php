<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/12/2025, 16:40
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoExtension.php
 * @date    03/11/2025
 * @time    13:15
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Twig\Extension;

use Idm\Bundle\Seo\Twig\Runtime\SeoRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SeoExtension extends AbstractExtension
{
	public function getFunctions (): array
	{
		return [
			new TwigFunction('idm_seo_title_*', [SeoRuntime::class, 'seoTitle']),
			new TwigFunction('idm_seo_title', [SeoRuntime::class, 'seoTitle']),
			new TwigFunction('idm_seo_meta', [SeoRuntime::class, 'seoMeta'], ['is_safe' => ['html']]),
			new TwigFunction('idm_seo_open_graph', [SeoRuntime::class, 'seoOpenGraphMeta'], ['is_safe' => ['html']]),
			new TwigFunction('idm_seo_twitter', [SeoRuntime::class, 'seoTwitterMeta'], ['is_safe' => ['html']]),
		];
	}
}
