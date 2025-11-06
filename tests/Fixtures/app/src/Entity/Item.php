<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/11/2025, 14:11
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Item.php
 * @date    06/11/2025
 * @time    14:10
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Idm\Bundle\Seo\Traits\Entity\SeoTrait;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
	use SeoTrait;

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private ?string $title = null;

	#[ORM\Column(type: Types::TEXT)]
	private ?string $content = null;

	#[ORM\Column(type: Types::SIMPLE_ARRAY)]
	private array $tags = [];

	public function getId (): ?int
	{
		return $this->id;
	}

	public function getTitle (): ?string
	{
		return $this->title;
	}

	public function setTitle (string $title): static
	{
		$this->title = $title;

		return $this;
	}

	public function getContent (): ?string
	{
		return $this->content;
	}

	public function setContent (string $content): static
	{
		$this->content = $content;

		return $this;
	}

	public function getTags (): array
	{
		return $this->tags;
	}

	public function setTags (array $tags): static
	{
		$this->tags = $tags;

		return $this;
	}
}
