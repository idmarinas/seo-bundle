<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 07/11/2025, 15:53
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
	public Meta $meta;

	#[ORM\OneToOne(targetEntity: OpenGraph::class, cascade: ['all'], orphanRemoval: true)]
	private OpenGraph $og;

	#[ORM\OneToOne(targetEntity: TwitterCard::class, cascade: ['all'], orphanRemoval: true)]
	private TwitterCard $twitter;

	public function __construct ()
	{
		$this->meta = new Meta();
		$this->og = new OpenGraph();
		$this->twitter = new TwitterCard();
	}

	public function getOg (): OpenGraph
	{
		return $this->og;
	}

	public function setOg (OpenGraph $og): self
	{
		$this->og = $og;

		return $this;
	}

	public function getTwitter (): TwitterCard
	{
		return $this->twitter;
	}

	public function setTwitter (TwitterCard $twitter): self
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
