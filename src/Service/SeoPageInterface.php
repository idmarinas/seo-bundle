<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 19:51
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoPageInterface.php
 * @date    25/11/2025
 * @time    19:51
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service;

use Idm\Bundle\Seo\Entity\SeoEntityInterface;

interface SeoPageInterface
{
	/**
	 * Add the entity for which the SEO will be generated and configure SEO of page.
	 */
	public function configure(?SeoEntityInterface $entity = null): self;

	/**
	 * Additional parameters required.
	 * They will be merged with the parameters passed in SitemapDynamic.
	 * This is not necessary if they have already been configured in SitemapDynamic.
	 */
	public function setRouteParams(array $routeParams): self;

	/**
	 * Only use this if no title has been entered for the entity.
	 */
	public function setTitle(string $title): self;

	/**
	 * Only used if no description has been entered in the entity.
	 */
	public function setDescription(string $description): self;

	/** Change the prefix for this page. */
	public function setPrefix(string $prefix): self;

	/** Change the suffix for this page. */
	public function setSuffix(string $suffix): self;
}
