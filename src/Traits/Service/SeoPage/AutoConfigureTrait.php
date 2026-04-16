<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 01/12/2025, 13:48
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AutoConfigureTrait.php
 * @date    01/12/2025
 * @time    13:48
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Service\SeoPage;

use Idm\Bundle\Seo\Entity\SeoEntityInterface;
use Idm\Bundle\Seo\Service\RouterGenerateSeoUrl;

trait AutoConfigureTrait
{
	private ?SeoEntityInterface $entity = null;

	public function configure(?SeoEntityInterface $entity = null): self
	{
		$this->entity = $entity;
		$this->seo->setEntity($entity?->getSeo());
		$this->configureTitle();
		$this->configureDescription();
		$this->configureCanonical();

		return $this;
	}

	private function configureTitle(): void
	{
		$title = $this->entity?->getSeo()?->getMeta()->title;

		if (empty($title) && !empty($this->entity) && method_exists($this->entity, 'getTitle')) {
			$title = $this->sanitize($this->entity->getTitle(), 70);
		}

		if (!empty($title)) {
			$this->title = $title;
		}
	}

	private function configureDescription(): void
	{
		$description = $this->entity?->getSeo()?->getMeta()->description;

		if (empty($description) && !empty($this->entity)) {
			if (method_exists($this->entity, 'getDescription')) {
				$description = $this->sanitize($this->entity->getDescription(), 160);
			} elseif (method_exists($this->entity, 'getContent')) {
				$description = $this->sanitize($this->entity->getContent(), 160);
			}
		}

		if (!empty($description)) {
			$this->description = $description;
		}
	}

	private function configureCanonical(): void
	{
		$params = $this->routeParams;
		$params['_locale'] = $this->locale;

		if (!empty($this->entity) && !empty($this->sitemap)) {
			$params = RouterGenerateSeoUrl::processUrlParameters($this->sitemap, $this->entity);
		}

		$this->canonical = $this->router->generateUrl($this->routeName, $params);

		array_walk(
			$this->localeAlternate,
			fn(&$url, $lang) => $url = $this->router->generateUrl(
				$this->routeName,
				array_merge($params, ['_locale' => $lang])
			)
		);
	}
}
