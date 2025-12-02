<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/12/2025, 16:55
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphMeta.php
 * @date    26/11/2025
 * @time    18:12
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Provider;

use Idm\Bundle\Seo\Entity\OpenGraph;
use ReflectionException;

final class OpenGraphMeta
{
	public readonly ?OpenGraph $entity;
	public string              $siteName;
	public string              $type;

	public function __construct (array $config)
	{
		$this->siteName = $config['site_name'];
		$this->type = $config['type'];
	}

	public function setEntity (?OpenGraph $og): self
	{
		$this->entity = $og;

		return $this;
	}

	/**
	 * @throws ReflectionException
	 */
	public function toArray (): array
	{
		return [
			'title'            => $this->entity?->title,
			'description'      => $this->entity?->description,
			'type'             => $this->entity?->type ?: $this->type,
			'site_name'        => $this->siteName,
			'url'              => null,
			'locale'           => null,
			'locale:alternate' => null,
		];
	}
}
