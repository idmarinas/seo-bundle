<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 19:13
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

use Idm\Bundle\Seo\Attribute\Sitemap;
use Idm\Bundle\Seo\Attribute\Sitemap\Prop;
use Idm\Bundle\Seo\Cache\CacheKeyEnum;
use Idm\Bundle\Seo\Cache\CacheTagEnum;
use Idm\Bundle\Seo\Sitemap\RouteAttributes;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use function Symfony\Component\String\u;

final readonly class RouterGenerateSeoUrl
{
	public function __construct(
		private RouterInterface                               $router,
		private CacheItemPoolInterface&TagAwareCacheInterface $cache,
		private array                                         $excludedRoutes,
		private DenormalizerInterface                         $denormalizer
	) {}

	public static function processUrlParameters(Sitemap $sitemap, array|object $result): array
	{
		$isObject = is_object($result);
		$params = $sitemap->urlParameters;
		$accessor = PropertyAccess::createPropertyAccessor();

		$params = array_filter($params, function ($v) use ($isObject, $sitemap, $result, $accessor) {
			if ($v instanceof Prop) {
				$property = $isObject ? $v->property : "[$v->property]";

				return $accessor->isReadable($result, $property);
			}

			return true;
		});
		array_walk($params, function (&$item) use ($isObject, $result, $accessor): void {
			if ($item instanceof Prop) {
				$property = $isObject ? $item->property : "[$item->property]";
				$item = $accessor->getValue($result, $property);
			}
		});

		return $params;
	}

	/** @internal */
	public function generateUrl(string $name, array $parameters = []): string
	{
		return $this->router->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
	}

	/**
	 * @return array<string, RouteAttributes>
	 * @throws InvalidArgumentException
	 */
	public function getAllRoutes(): array
	{
		return $this->cache->get(CacheKeyEnum::ROUTES_LIST->name, function (ItemInterface $item) {
			$item->tag([
				CacheTagEnum::ROUTES_LIST->value,
				CacheTagEnum::ROUTES_LIST->suffix('.all'),
				CacheTagEnum::ROUTES_LIST->suffix('.with.attributes'),
			]);

			$all = $this->router->getRouteCollection()->all();
			$all = array_filter($all, fn(string $r) => !u($r)->startsWith($this->excludedRoutes), ARRAY_FILTER_USE_KEY);

			return array_map(fn($route) => new RouteAttributes($route, $this->denormalizer), $all);
		});
	}

	public function setScheme(string $scheme): self
	{
		$this->router->getContext()->setScheme($scheme);

		return $this;
	}
}
