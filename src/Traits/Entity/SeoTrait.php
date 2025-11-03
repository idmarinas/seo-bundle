<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/11/2025, 12:04
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoTrait.php
 * @date    21/10/2025
 * @time    18:07
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;
use Idm\Bundle\Seo\Entity\Seo;

trait SeoTrait
{
	#[ORM\Embedded(class: Seo::class)]
	protected Seo $seo;

	public function getSeo (): Seo
	{
		return $this->seo;
	}

	public function setSeo (Seo $seo): static
	{
		$this->seo = $seo;

		return $this;
	}
}
