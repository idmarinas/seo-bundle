<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 28/11/2025, 17:51
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    RouterGeneratorSeoUrl.php
 * @date    28/11/2025
 * @time    17:37
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service;

use Idm\Bundle\Seo\Attributes\Sitemap\SitemapDynamic;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

final readonly class RouterGeneratorSeoUrl
{
	public function __construct (private RouterInterface $router) {}

	protected static function processUrlParameters (array $params, array|object $result, SitemapDynamic $sitemap): array
	{
		$isObject = $result instanceof $sitemap->entity;

		$params = array_filter($params, function ($v) use ($isObject, $sitemap, $result) {
			$method = 'get' . ucfirst($v);

			return $isObject ? method_exists($sitemap->entity, $method) : isset($result[$v]);
		});
		array_walk($params, function (&$item) use ($isObject, $result) {
			$method = 'get' . ucfirst($item);
			$item = $isObject ? $result->{$method}() : $result[$item];
		});

		return $params;
	}

	/** @internal */
	public function generateUrl (string $name, array $parameters = []): string
	{
		return $this->router->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
	}

	/**
	 * @return array<string, Route>
	 */
	public function getAllRoutes (): array
	{
		return $this->router->getRouteCollection()->all();
	}

	public function setScheme (string $scheme): self
	{
		$this->router->getContext()->setScheme($scheme);

		return $this;
	}
}
