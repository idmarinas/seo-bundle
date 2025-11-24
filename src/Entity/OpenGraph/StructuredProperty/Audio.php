<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 13:23
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Audio.php
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
class Audio
{
	/**
	 * og:audio or og:audio:url
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Url(normalizer: 'trim')]
	#[Assert\NotNull]
	public string $url = '';

	/**
	 * og:audio:secure_url
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Url(protocols: ['https'])]
	#[Assert\NotNull]
	public string $secureUrl = '';

	/**
	 * og:audio:type
	 * MIME type del audio (audio/mpeg, audio/ogg, etc.)
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Regex(pattern: '#^audio/[\w\-\+\.]+$#')]
	#[Assert\NotNull]
	public string $type = '';
}
