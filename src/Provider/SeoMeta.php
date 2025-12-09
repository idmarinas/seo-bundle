<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 19:57
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoConfig.php
 * @date    03/11/2025
 * @time    19:50
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Provider;

use Idm\Bundle\Seo\Entity\Seo;
use function Symfony\Component\String\u;

final readonly class SeoMeta
{
	public Meta          $meta;
	public OpenGraphMeta $og;
	public TwitterMeta   $twitter;

	public function __construct (array $config)
	{
		$this->meta = new Meta($config);
		$this->og = new OpenGraphMeta($config['open_graph']);
		$this->twitter = new TwitterMeta($config['twitter']);
	}

	public function getTitleTemplate (string $type): string
	{
		return $this->meta->title->getTemplate($type);
	}

	public function getFormatedTitle (string $type, string $title): string
	{
		return u($this->getTitleTemplate($type))
			->replace('{title}', $title)
			->replace('{separator}', $this->meta->title->separator)
			->replace('{prefix}', $this->meta->title->prefix)
			->replace('{suffix}', $this->meta->title->suffix)
			->trim()->trim($this->meta->title->separator)->trim()
			->toString()
		;
	}

	public function setEntity (?Seo $entity): self
	{
		$this->meta->setEntity($entity?->getMeta());
		$this->og->setEntity($entity?->getOg());
		$this->twitter->setEntity($entity?->getTwitter());

		return $this;
	}
}
