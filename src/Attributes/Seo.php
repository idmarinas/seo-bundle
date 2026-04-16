<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/12/2025, 15:00
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Seo.php
 * @date    01/12/2025
 * @time    14:07
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Attributes;

use Attribute;
use Symfony\Contracts\Translation\TranslatableInterface;

/**
 * Automatically configure SEO for this page
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final class Seo
{
	/**
	 * @param string                       $entity      The fully qualified entity class name to search in arguments.
	 * @param string|TranslatableInterface $title       Title for the page if is static. Can use a Translatable Message.
	 * @param string|TranslatableInterface $description Description for the page if is static. Can use a Translatable Message.
	 */
	public function __construct(
		public string                       $entity = '',
		public string|TranslatableInterface $title = '',
		public string|TranslatableInterface $description = '',
	) {}
}
