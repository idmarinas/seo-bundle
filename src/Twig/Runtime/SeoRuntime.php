<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/11/2025, 16:53
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoRuntime.php
 * @date    03/11/2025
 * @time    13:15
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Twig\Runtime;

use Idm\Bundle\Seo\Service\SeoPage;
use Twig\Extension\RuntimeExtensionInterface;
use function Symfony\Component\String\u;

final readonly class SeoRuntime implements RuntimeExtensionInterface
{
	public function __construct (private array $templates, private SeoPage $seoPage)
	{
		// Inject dependencies if needed
	}

	public function idmSeoTitle (string $type = ''): string
	{
		$template = $this->templates[$type] ?? $this->templates['title'];

		return u($template)
			->replace('{title}', $this->seoPage->getSeo()?->title ?? '')
			->replace('{separator}', $this->seoPage->getSeparator())
			->replace('{prefix}', $this->seoPage->getPrefix())
			->replace('{suffix}', $this->seoPage->getSuffix())
			->trim()
			->trim($this->seoPage->getSeparator())
			->trim()
			->toString()
		;
	}
}
