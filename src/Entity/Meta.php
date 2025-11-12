<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 12/11/2025, 13:42
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Meta.php
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
use Idm\Bundle\Seo\Validator\NoConflictingRobots;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Meta
{
	#[ORM\Column]
	#[Assert\Length(min: 0, max: 160)]
	#[Assert\NotBlank(allowNull: false)]
	public string $title = '';

	#[ORM\Column]
	#[Assert\NotNull]
	public string $description = '';

	#[ORM\Column]
	#[Assert\Url(normalizer: 'trim')]
	#[Assert\NotNull]
	public string $canonical = '';

	/**
	 * @var string[]
	 */
	#[ORM\Column(type: Types::JSON)]
	#[Assert\All([new Assert\NotNull])]
	public array $keywords = [];

	/**
	 * @var string[]
	 */
	#[ORM\Column(type: Types::JSON)]
	#[NoConflictingRobots]
	public array $robots = ['index', 'follow'];
}
