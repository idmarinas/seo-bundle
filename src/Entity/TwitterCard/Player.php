<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 08/12/2025, 11:52
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file Player.php
 * @date 04/11/2025
 * @time 20:00
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Entity\TwitterCard;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Player
{
	#[ORM\Column]
	#[Assert\Url(protocols: ['https'], normalizer: 'trim', groups: ['twitter-card-player'])]
	#[Assert\NotBlank(allowNull: false, groups: ['twitter-card-player'])]
	public string $url = '';

	#[ORM\Column]
	#[Assert\GreaterThanOrEqual(262, groups: ['twitter-card-player'])]
	#[Assert\NotBlank(allowNull: false, groups: ['twitter-card-player'])]
	public int $width = 262;

	#[ORM\Column]
	#[Assert\GreaterThanOrEqual(262, groups: ['twitter-card-player'])]
	#[Assert\NotBlank(allowNull: false, groups: ['twitter-card-player'])]
	public int $height = 262;
}
