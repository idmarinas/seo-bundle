<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/12/2025, 13:40
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Meta.php
 * @date    26/11/2025
 * @time    18:12
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Provider;

use Idm\Bundle\Seo\Entity\Meta as MetaEntity;
use Idm\Bundle\Seo\Provider\Meta\TitleMeta;
use ReflectionException;
use ReflectionProperty;

final class Meta
{
	public readonly ?MetaEntity $entity;
	public readonly TitleMeta   $title;
	public string               $description;

	public function __construct (array $config)
	{
		$this->title = new TitleMeta($config['title']);
		$this->description = $config['description'];
	}

	public function setEntity (?MetaEntity $meta): self
	{
		$this->entity = $meta;

		return $this;
	}

	/**
	 * @throws ReflectionException
	 */
	public function toArray (): array
	{
		if (!(new ReflectionProperty($this, 'entity'))->isInitialized($this)) {
			$this->setEntity(null);
		}

		return [
			'title'       => $this->entity?->title,
			'description' => $this->entity?->description,
			'keywords'    => implode(',', $this->entity?->keywords ?? []),
			'robots'      => implode(',', $this->entity?->robots ?? []),
		];
	}
}
