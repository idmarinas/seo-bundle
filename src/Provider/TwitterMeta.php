<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/12/2025, 13:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    TwitterMeta.php
 * @date    26/11/2025
 * @time    18:12
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Provider;

use Idm\Bundle\Seo\Entity\TwitterCard;
use ReflectionException;
use ReflectionProperty;

final class TwitterMeta
{
	public readonly ?TwitterCard $entity;
	public string                $site;
	public string                $card;
	public string                $creator;

	public function __construct (array $config)
	{
		$this->site = $config['site'];
		$this->card = $config['card'];
		$this->creator = $config['creator'];
	}

	public function setEntity (?TwitterCard $entity): self
	{
		$this->entity = $entity;

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

		$card = [
			'card'        => $this->entity?->card ?: $this->card,
			'site'        => $this->site,
			'creator'     => $this->entity?->creator ?: $this->creator,
			'title'       => $this->entity?->title,
			'description' => $this->entity?->description,
			'image'       => $this->entity?->image,
			'image:alt'   => $this->entity?->imageAlt,
		];

		if ('app' === $card['card']) {
			$card['app:id:googleplay'] = $this->entity?->app->googleplay->id;
			$card['app:name:googleplay'] = $this->entity?->app->googleplay->name;
			$card['app:url:googleplay'] = $this->entity?->app->googleplay->url;

			$card['app:id:iphone'] = $this->entity?->app->iphone->id;
			$card['app:name:iphone'] = $this->entity?->app->iphone->name;
			$card['app:url:iphone'] = $this->entity?->app->iphone->url;

			$card['app:id:ipad'] = $this->entity?->app->ipad->id;
			$card['app:name:ipad'] = $this->entity?->app->ipad->name;
			$card['app:url:ipad'] = $this->entity?->app->ipad->url;
		} elseif ('player' === $card['card']) {
			$card['player'] = $this->entity?->player->url;
			$card['player:width'] = $this->entity?->player->width;
			$card['player:height'] = $this->entity?->player->height;
		}

		return $card;
	}
}
