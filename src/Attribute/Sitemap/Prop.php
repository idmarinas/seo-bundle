<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/12/2025, 16:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Prop.php
 * @date    05/12/2025
 * @time    16:09
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Attribute\Sitemap;

/**
 * Represents a reference to an entity property for URL parameter resolution.
 *
 * Uses Symfony PropertyAccess component to access entity properties, which supports:
 * - Public properties: 'propertyName'
 * - Getters: 'propertyName' (will call getPropertyName())
 * - Issers: 'propertyName' (will call isPropertyName())
 * - Hassers: 'propertyName' (will call hasPropertyName())
 * - Nested properties: 'category.name' (will access $entity->getCategory()->getName())
 */
final readonly class Prop
{
	public function __construct(
		public string $property
	) {}

	/**
	 * Creates a reference to an entity property using a property path.
	 */
	public static function new(string $propertyPath): self
	{
		return new self($propertyPath);
	}
}
