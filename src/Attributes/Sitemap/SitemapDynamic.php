<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 04/06/2025, 21:32
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapDynamic.php
 * @date    29/05/2025
 * @time    13:33
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Attributes\Sitemap;

use Attribute;
use DateMalformedStringException;
use Doctrine\Common\Collections\Criteria;
use Idm\Bundle\Seo\Sitemap\Node\Url;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final readonly class SitemapDynamic implements SitemapInterface
{
	/**
	 * This attribute facilitates dynamic sitemap generation from database entities.
	 *
	 * @param string          $name               The unique identifier for this sitemap section.
	 * @param string          $entity             The fully qualified entity class name to fetch data from.
	 * @param string|Criteria $criteria           Repository method name or Doctrine Criteria object to filter results.
	 *                                            When a Criteria object is provided, it will be used with the
	 *                                            `EntityRepository::matching()` method.
	 *                                            When string is provided, it will be used as a repository method name
	 *                                            (default: 'findAll').
	 * @param string          $updatedAtField     The entity field name to use for retrieving the last modification
	 *                                            date. Used to populate the 'lastmod' field in the sitemap XML (default:
	 *                                            'updatedAt').
	 * @param array           $urlParameters      Associative array where the key is the parameter name and the value is
	 *                                            the entity field name to get its value. These will be used to
	 *                                            construct the URL parameters (default: []).
	 *                                            <code>Example: ['slug' => 'slug', 'category' => 'categorySlug']</code>
	 *                                            This would generate URLs with parameters from the entity fields.
	 */
	public function __construct (
		public string          $name,
		public string          $entity,
		public string|Criteria $criteria = 'findAll',
		public string          $updatedAtField = 'updatedAt',
		public array           $urlParameters = [],
	) {}

	/**
	 * @throws DateMalformedStringException
	 */
	public function getUrl (string $loc): Url
	{
		return new Url($loc);
	}
}
