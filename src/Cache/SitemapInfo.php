<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/06/2025, 19:20
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapInfo.php
 * @date    02/06/2025
 * @time    17:52
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Cache;

use DateTimeInterface;
use DOMException;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Psr\Cache\InvalidArgumentException;

final class SitemapInfo
{
	public const int LIMIT_ITEMS = 50_000;
	public const int LIMIT_BYTES = 52_428_800; // 50MB

	private string $name;
	private bool   $index;

	public function __construct (
		private DateTimeInterface $updatedAt,
		private SitemapFile       $document,
	) {}

	public function getDocument (): SitemapFile
	{
		return $this->document;
	}

	public function setDocument (SitemapFile $document): self
	{
		$this->document = $document;

		return $this;
	}

	public function getUpdatedAt (): DateTimeInterface
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt (DateTimeInterface $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	public function __serialize (): array
	{
		dump(__FUNCTION__);

		return [
			'updated_at' => $this->updatedAt,
			'sitemap'    => $this->document->getDocument()->saveXML(),
			'name'       => $this->document->getName(),
			'index'      => $this->document->isIndex(),
		];
	}

	/**
	 * @throws DOMException
	 * @throws InvalidArgumentException
	 */
	public function __unserialize (array $data): void
	{
		dump(__FUNCTION__);
		$this->updatedAt = $data['updated_at'];
		$this->name = $data['name'];
		$this->index = $data['index'];

		$sitemap = new SitemapFile($this->name, $this->index);
		$sitemap->load($data['sitemap']);

		$this->document = $sitemap;
	}
}
