<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/11/2025, 15:31
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Seo.php
 * @date    21/10/2025
 * @time    17:55
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Idm\Bundle\Common\Traits\Entity\UuidTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'idm_seo__seo_data')]
#[Assert\Cascade]
class Seo
{
	use UuidTrait;

	#[ORM\Embedded(class: Meta::class)]
	private Meta $meta;

	#[ORM\OneToOne(targetEntity: OpenGraph::class, cascade: ['all'])]
	private OpenGraph $op;

	#[ORM\OneToOne(targetEntity: TwitterCard::class, cascade: ['all'])]
	private TwitterCard $twitter;

	public function __construct ()
	{
		$this->meta = new Meta();
		$this->op = new OpenGraph();
		$this->twitter = new TwitterCard();
	}

	public function getOpenGraph (): OpenGraph
	{
		return $this->op;
	}

	public function setOpenGraph (OpenGraph $op): self
	{
		$this->op = $op;

		return $this;
	}

	public function getTwitterCard (): TwitterCard
	{
		return $this->twitter;
	}

	public function setTwitterCard (TwitterCard $twitter): self
	{
		$this->twitter = $twitter;

		return $this;
	}

	public function getMeta (): Meta
	{
		return $this->meta;
	}

	public function setMeta (Meta $meta): self
	{
		$this->meta = $meta;

		return $this;
	}
}
