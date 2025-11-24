<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 13:23
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Image.php
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
class Image extends Video
{
	/**
	 * og:image:alt
	 * Texto alternativo de la imagen para accesibilidad
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotNull]
	public string $alt = '';

	/**
	 * og:image:type
	 * MIME type de la imagen (image/jpeg, image/png, etc.)
	 * Sobrescribe la validación de Video para permitir image/*
	 */
	#[Assert\Regex(pattern: '/^image\/[\w\-\+\.]+$/')]
	public string $type = '';
}
