<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 01/12/2025, 13:25
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SanitizerTrait.php
 * @date    01/12/2025
 * @time    13:23
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Service\SeoPage;

use function Symfony\Component\String\u;

trait SanitizerTrait
{
	protected function sanitize(string $string, int|false $truncate = false): string
	{
		$string = u(strip_tags($string))
			->collapseWhitespace()
		;

		if (false !== $truncate) {
			return $string->truncate($truncate, '...')->toString();
		}

		return $string->toString();
	}
}
