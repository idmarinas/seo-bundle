<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 12/11/2025, 13:56
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AppCard.php
 * @date    04/11/2025
 * @time    20:24
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity\TwitterCard;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class AppCard
{
	#[ORM\Column(type: Types::STRING)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotBlank(allowNull: false, groups: ['twitter-card-app'])]
	public string $id = '';

	#[ORM\Column(type: Types::STRING)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotBlank(allowNull: false, groups: ['twitter-card-app'])]
	public string $name = '';

	#[ORM\Column(type: Types::STRING)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotNull]
	public string $url = '';
}
