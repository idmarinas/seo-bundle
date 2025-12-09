<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 18:57
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file Sitemap.php
 * @date 05/12/2025
 * @time 15:02
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Attributes;

use Attribute;
use DateMalformedStringException;
use Doctrine\Common\Collections\Criteria;
use Idm\Bundle\Seo\Attributes\Sitemap\Prop;
use Idm\Bundle\Seo\Sitemap\Node\Url;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
final class Sitemap implements SitemapInterface
{

	/**
	 * This attribute facilitates sitemap generation for both static and dynamic pages.
	 *
	 * When both `$name` and `$entity` contain string values, the sitemap is considered dynamic.
	 * For dynamic sitemaps, $priority and $changefreq are applied to each entry/item.
	 *
	 * @param string|null              $name            The unique identifier for this sitemap section. When
	 *                                                  provided
	 *                                                  along with $entity, the sitemap becomes dynamic.
	 * @param string                   $priority        The priority value for sitemap entries. For dynamic sitemaps,
	 *                                                  this applies to each item (default: '0.5').
	 * @param string                   $changefreq      The change frequency for sitemap entries. For dynamic sitemaps,
	 *                                                  this applies to each item (default: 'weekly').
	 * @param string|null              $entity          The fully qualified entity class name to fetch data from. When
	 *                                                  provided along with $name, the sitemap becomes dynamic.
	 * @param string|Criteria          $criteria        Repository method name or Doctrine Criteria object to filter
	 *                                                  results. When a Criteria object is provided, it will be used with
	 *                                                  the `EntityRepository::matching()` method.
	 *                                                  When string is provided, it will be used as a repository method
	 *                                                  name (default: 'findAll').
	 * @param string                   $updatedAtField  The entity field name to use for retrieving the last modification
	 *                                                  date. Used to populate the 'lastmod' field in the sitemap XML
	 *                                                  (default: 'updatedAt').
	 * @param array<string,mixed|Prop> $urlParameters   Associative array where the key is the URL parameter name and
	 *                                                  the value can be:
	 *                                                  - Prop: to access an entity property using PropertyAccess
	 *                                                  component (supports public properties, getters, issers,
	 *                                                  hassers, and nested properties)
	 *                                                  - Any other type (string, int, bool, etc.): used directly as a
	 *                                                  static value (default: []).
	 *                                                  <code>Example:
	 *                                                  [
	 *                                                  'slug' => new Prop('slug'),
	 *                                                  'category_id' => new Prop('category.id'),
	 *                                                  'active' => new Prop('active'),
	 *                                                  'lang' => 'es',    // static string value
	 *                                                  'country' => 'ES', // static string value
	 *                                                  'page' => 1,       // static numeric value
	 *                                                  'featured' => true // static boolean value
	 *                                                  ]
	 *                                                  </code>
	 */
	public function __construct (
		public ?string         $name = null,
		public string          $priority = '0.5',
		public string          $changefreq = self::CHANGEFREQ_WEEKLY,
		public ?string         $entity = null,
		public string|Criteria $criteria = 'findAll',
		public string          $updatedAtField = 'updatedAt',
		public array           $urlParameters = [],
	) {}

	/**
	 * @throws DateMalformedStringException
	 */
	public function getUrl (string $loc): Url
	{
		return new Url($loc, null, $this->changefreq, $this->priority);
	}

	/**
	 * @inheritDoc
	 */
	public function isDynamic (): bool
	{
		return !empty($this->name) && !empty($this->entity);
	}
}
