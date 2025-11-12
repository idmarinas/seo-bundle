<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 12/11/2025, 17:02
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoColumnTrait.php
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
use Symfony\Component\Validator\Constraints as Assert;

trait SeoColumnTrait
{
	#[ORM\OneToOne(targetEntity: Seo::class, cascade: ['all'], orphanRemoval: true)]
	#[Assert\Valid]
	protected ?Seo $seo = null;

	public function getSeo (): ?Seo
	{
		return $this->seo;
	}

	public function setSeo (?Seo $seo): static
	{
		$this->seo = $seo;

		return $this;
	}

	public function hasSeo (): bool
	{
		return $this->seo instanceof Seo;
	}
}
