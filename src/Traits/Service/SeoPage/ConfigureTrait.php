<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/12/2025, 17:05
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    ConfigureTrait.php
 * @date    26/11/2025
 * @time    16:32
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Service\SeoPage;

use Idm\Bundle\Seo\Attributes\Sitemap\SitemapDynamic;

trait ConfigureTrait
{
	protected ?string       $routeName   = null;
	protected array         $routeParams = [];
	protected ?string       $title       = null;
	protected ?string       $description = null;
	protected ?string       $canonical   = null;
	protected ?string       $locale      = null;
	private ?SitemapDynamic $sitemap     = null;

	public function setSitemap (SitemapDynamic $sitemap): self
	{
		$this->sitemap = $sitemap;

		return $this;
	}

	public function setLocale (string $locale): self
	{
		$this->locale = $locale;

		return $this;
	}

	public function setRouteName (string $name): self
	{
		$this->routeName = $name;

		return $this;
	}

	public function setTitle (string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function setDescription (string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function setRouteParams (array $routeParams): self
	{
		if ([] !== array_diff($routeParams, $this->routeParams)) {
			$this->routeParams = $routeParams;
			$this->configureCanonical();
		}

		return $this;
	}
}
