<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 22/10/2025, 15:32
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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Embeddable]
class Seo implements Stringable
{
	/**
	 * @var string
	 */
	#[ORM\Column]
	public string $title;

	/**
	 * @var string|null
	 */
	#[ORM\Column(type: Types::TEXT, nullable: true)]
	public ?string $description = null;

	/**
	 * @var string|null
	 */
	#[ORM\Column(nullable: true)]
	public ?string $keywords = null;

	/**
	 * @var string|null
	 */
	#[ORM\Column(nullable: true)]
	public ?string $canonical = null;

	/**
	 * @var string|null
	 */
	#[ORM\Column(nullable: true)]
	public ?string $key = null;

	/**
	 * @var bool
	 */
	#[ORM\Column(type: Types::BOOLEAN)]
	public bool $sitemap = true;

	/**
	 * @var array<int, string>
	 */
	#[ORM\Column(type: Types::JSON)]
	public array $robots = [];

	public function __toString (): string
	{
		return $this->title;
	}
}
