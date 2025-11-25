<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 11:22
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Video.php
 * @date    05/11/2025
 * @time    19:42
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity\OpenGraph\StructuredProperty;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Video extends Audio
{
	/**
	 * og:video:width
	 * Ancho del video en píxeles (mínimo 262px según spec)
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Positive]
	#[Assert\GreaterThanOrEqual(262)]
	public int $width = 262;

	/**
	 * og:video:height
	 * Alto del video en píxeles (mínimo 262px según spec)
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Positive]
	#[Assert\GreaterThanOrEqual(262)]
	public int $height = 262;

	/**
	 * og:video:type
	 * MIME type del video (video/mp4, video/webm, etc.)
	 * Sobrescribe la validación de Audio para permitir video/*
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Regex(pattern: '#^video/[\w\-\+\.]+$#')]
	public string $type = '';
}
