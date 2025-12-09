<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 19:15
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file RouteAttributes.php
 * @date 08/12/2025
 * @time 18:15
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap;

use ArrayObject;
use Idm\Bundle\Seo\Attributes\Seo;
use Idm\Bundle\Seo\Attributes\Sitemap;
use Idm\Bundle\Seo\Attributes\SitemapInterface;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Routing\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use function Symfony\Component\String\u;

final readonly class RouteAttributes
{
	/**
	 * @var ArrayObject<string, ArrayObject<int, ArrayObject>> $attributes
	 */
	public ArrayObject $attributes;

	/**
	 * @throws ExceptionInterface
	 */
	public function __construct (public Route $route, private DenormalizerInterface $denormalizer)
	{
		$this->attributes = new ArrayObject($this->getAttributes());
	}

	/**
	 * @return ArrayObject
	 * @throws ExceptionInterface
	 * @internal
	 */
	private function getAttributes (): ArrayObject
	{
		$controller = $this->route->getDefault('_controller');
		$controller = is_array($controller) ? $controller[0] : $controller;
		$controller = u($controller)->trim();

		if ($controller->isEmpty()) {
			return new ArrayObject();
		}

		$templates = [
			'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController',
		];

		return match (true) {
			$controller->containsAny('::')       => $this->getAttributesFromController($controller),
			$controller->containsAny($templates) => $this->getAttributesFromTemplate($this->route),
			default                              => new ArrayObject(),
		};
	}

	/** @throws ExceptionInterface
	 * @internal
	 */
	private function getAttributesFromTemplate (Route $route): ArrayObject
	{
		$routes = new ArrayObject();

		if ($sitemap = $route->getOption('sitemap') ?? true) {
			$map = is_array($sitemap) ? $sitemap : ['changefreq' => SitemapInterface::CHANGEFREQ_YEARLY];
			$map = $this->denormalizer->denormalize($map, Sitemap::class);
			$routes->offsetSet(Sitemap::class, new ArrayObject([$map]));
		}

		if ($seo = $route->getOption('seo') ?? true) {
			$seo = is_array($seo) ? $seo : [];
			$seo = $this->denormalizer->denormalize($seo, Seo::class);
			$routes->offsetSet(Seo::class, new ArrayObject([$seo]));
		}

		return $routes;
	}

	/** @internal */
	private function getAttributesFromController (string $_controller): ArrayObject
	{
		$attributes = new ArrayObject();
		try {
			[$controller, $method] = explode('::', $_controller);
			$ref = new ReflectionMethod($controller, $method);

			$attrs = $ref->getAttributes();

			if (!empty($attrs)) {
				foreach ($attrs as $attribute) {
					if (!$attributes->offsetExists($attribute->getName())) {
						$attributes->offsetSet($attribute->getName(), new ArrayObject());
					}

					$attributes[$attribute->getName()]->append($attribute->newInstance());
				}
			}
		} catch (ReflectionException) {
		}

		return $attributes;
	}
}
