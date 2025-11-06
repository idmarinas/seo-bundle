<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/11/2025, 11:21
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Article.php
 * @date    05/11/2025
 * @time    15:36
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity\OpenGraph\Type;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Article
{
	#[ORM\Column]
	#[Assert\NotBlank]
	public DateTimeInterface $publishedTime;

	#[ORM\Column]
	#[Assert\NotBlank]
	public DateTimeInterface $modifiedTime;

	#[ORM\Column]
	#[Assert\NotBlank]
	public DateTimeInterface $expirationTime;

	#[ORM\Column(type: Types::JSON)]
	#[Assert\NotNull]
	public array $author = [];

	#[ORM\Column]
	#[Assert\NotNull]
	public string $section = '';

	#[ORM\Column(type: Types::JSON)]
	#[Assert\NotNull]
	public array $tag = [];

//article:published_time - datetime - When the article was first published.
//article:modified_time - datetime - When the article was last changed.
//article:expiration_time - datetime - When the article is out of date after.
//article:author - profile array - Writers of the article.
//article:section - string - A high-level section name. E.g. Technology
//article:tag - string array - Tag words associated with this article.
}
