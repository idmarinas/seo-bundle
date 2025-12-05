<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/12/2025, 14:48
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
use Idm\Bundle\Seo\Traits\Twig\RunTime\SeoRuntime\BuildTagsTrait;
use ReflectionException;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class SeoRuntime implements RuntimeExtensionInterface
{
	use BuildTagsTrait;

	public function __construct (private SeoPage $seoPage) {}

	public function seoTitle (string $type = ''): string
	{
		return $this->seoPage->getFormatedTitle($type);
	}

	/**
	 * @throws ReflectionException
	 */
	public function seoMeta (): string
	{
		if (!$this->seoPage->isEnabled()) {
			return '';
		}

		$html = "<!-- Primary Meta Tags -->\n";
		$html .= self::buildHtmlMetaTags($this->seoPage->getMetaTags(), '');

		if (!empty($canonical = $this->seoPage->getCanonical())) {
			$html .= self::buildHtmlTagCanonical($canonical);
		}

		if (!empty($locales = $this->seoPage->getLocaleAlternate())) {
			foreach ($locales as $lang => $url) {
				$html .= self::buildHtmlTagLocaleAlternate($lang, $url);
			}
		}

		return $html;
	}

	/**
	 * @throws ReflectionException
	 */
	public function seoOpenGraphMeta (): string
	{
		if (!$this->seoPage->isEnabled()) {
			return '';
		}

		$html = "<!-- Open Graph / Facebook -->\n";

		return $html . self::buildHtmlMetaTags($this->seoPage->getOpenGraphTags(), 'og');
	}

	/**
	 * @throws ReflectionException
	 */
	public function seoTwitterMeta (): string
	{
		if (!$this->seoPage->isEnabled()) {
			return '';
		}

		$html = "<!-- X (Twitter) -->\n";

		return $html . self::buildHtmlMetaTags($this->seoPage->getTwitterTags(), 'twitter');
	}
}
