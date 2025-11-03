<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/11/2025, 17:08
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoPage.php
 * @date    03/11/2025
 * @time    15:35
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service;

use Idm\Bundle\Seo\Entity\Seo;

final class SeoPage
{
	private ?Seo   $seo = null;
	private string $title;
	private string $prefix;
	private string $suffix;
	private string $separator;

	public function __construct (array $config = [])
	{
		$this->configure($config);
	}

	public function getTitle (): string
	{
		return $this->title;
	}

	public function getPrefix (): string
	{
		return $this->prefix;
	}

	public function getSeparator (): string
	{
		return $this->separator;
	}

	public function getSuffix (): string
	{
		return $this->suffix;
	}

	public function getSeo (): ?Seo
	{
		return $this->seo;
	}

	public function setSeo (Seo $seo): self
	{
		$this->seo = $seo;

		return $this;
	}

	private function configure (array $config): void
	{
		$this->title = $config['title']['default'];
		$this->prefix = $config['title']['prefix'];
		$this->suffix = $config['title']['suffix'];
		$this->separator = $config['title']['separator'];
	}
}
