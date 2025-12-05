<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/12/2025, 16:51
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    RouterGenerateSeoUrl.php
 * @date    28/11/2025
 * @time    17:37
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service;

use Idm\Bundle\Seo\Attributes\Sitemap;
use Idm\Bundle\Seo\Attributes\SitemapInterface;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use function Symfony\Component\String\u;

final readonly class RouterGenerateSeoUrl
{
	public function __construct (private RouterInterface $router) {}

	public static function processUrlParameters (Sitemap $sitemap, array|object $result): array
	{
		$isObject = is_object($result);
		$params = $sitemap->urlParameters;
		$accessor = PropertyAccess::createPropertyAccessor();

		$params = array_filter($params, function ($v) use ($isObject, $sitemap, $result, $accessor) {
			if ($v instanceof Sitemap\Prop) {
				$property = $isObject ? $v->property : "[$v->property]";

				return $accessor->isReadable($result, $property);
			}

			return true;
		});
		array_walk($params, function (&$item) use ($isObject, $result, $accessor) {
			if ($item instanceof Sitemap\Prop) {
				$property = $isObject ? $item->property : "[$item->property]";
				$item = $accessor->getValue($result, $property);
			}
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

	public function findRouteByName (string $name): ?Route
	{
		return $this->router->getRouteCollection()->get($name);
	}

	public function setScheme (string $scheme): self
	{
		$this->router->getContext()->setScheme($scheme);

		return $this;
	}

	public function getSitemapFromRoute (Route $route): ?SitemapInterface
	{
		$controller = $route->getDefault('_controller');
		$controller = is_array($controller) ? $controller[0] : $controller;
		$controller = u($controller)->trim();

		if ($controller->isEmpty()) {
			return null;
		}

		$templates = [
			'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController',
			'Symfony\\Bundle\\FrameworkBundle\\Controller\\RedirectController',
		];

		return match (true) {
			$controller->containsAny('::')       => $this->getSitemapFromAttribute($controller),
			$controller->containsAny($templates) => $this->getSitemapFromTemplate($route),
			default                              => null,
		};
	}

	/** @internal */
	private function getSitemapFromTemplate (Route $route): ?SitemapInterface
	{
		if ($route->getOption('sitemap') ?? true) {
			return new Sitemap(changefreq: SitemapInterface::CHANGEFREQ_YEARLY);
		}

		return null;
	}

	/** @internal */
	private function getSitemapFromAttribute (string $_controller): ?SitemapInterface
	{
		try {
			[$controller, $method] = explode('::', $_controller);
			$ref = new ReflectionMethod($controller, $method);

			$attributes = $ref->getAttributes(SitemapInterface::class, ReflectionAttribute::IS_INSTANCEOF);

			if ([] === $attributes) {
				return null;
			}

			/** @var SitemapInterface */
			return $attributes[0]->newInstance();
		} catch (ReflectionException) {
			return null;
		}
	}
}
