<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 01/12/2025, 18:44
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    BuildTagsTrait.php
 * @date    27/11/2025
 * @time    20:13
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Twig\RunTime\SeoRuntime;

trait BuildTagsTrait
{
	/**
	 * @param array  $tags   Associative array of properties and values
	 * @param string $prefix Prefix to prepend to all properties (og, twitter)
	 *
	 * @return string
	 */
	public static function buildHtmlMetaTags (array $tags, string $prefix): string
	{
		$attribute = str_starts_with($prefix, 'og') ? 'property' : 'name';

		$html = '';
		$metas = array_filter($tags);

		foreach ($metas as $property => $content) {
			$propertyContent = $prefix ? $prefix . ':' . $property : $property;
			if (is_int($property)) {
				$propertyContent = $prefix;
			}

			if (is_array($content)) {
				$html .= self::buildHtmlMetaTags($content, $propertyContent);
			} else {
				$html .= sprintf('<meta %1$s="%2$s" content="%3$s" />', $attribute, $propertyContent, $content);
			}
		}

		return $html;
	}

	public static function buildHtmlTagCanonical (string $url): string
	{
		return sprintf('<link rel="canonical" href="%1$s" />', $url);
	}

	public static function buildHtmlTagLocaleAlternate (string $locale, string $url): string
	{
		return sprintf('<link rel="alternate" hreflang="%1$s" href="%2$s" />', $locale, $url);
	}
}
