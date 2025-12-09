<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 18:53
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file SitemapGenerator.php
 * @date 08/12/2025
 * @time 14:52
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Service;

use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use DOMException;
use Exception;
use Idm\Bundle\Seo\Attributes\Sitemap as SitemapAttribute;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Sitemap\RouteAttributes;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

final readonly class SitemapGenerator
{
	public function __construct (
		private RouterGenerateSeoUrl   $router,
		private EntityManagerInterface $entityManager,
		private string                 $defaultScheme
	) {
		$this->router->setScheme($this->defaultScheme);
	}

	protected static function processLastUpdated (array|object $result, SitemapAttribute $sitemap): mixed
	{
		$accessor = PropertyAccess::createPropertyAccessorBuilder()
			->disableExceptionOnInvalidPropertyPath()
			->getPropertyAccessor()
		;
		$isObject = $result instanceof $sitemap->entity;
		$property = $isObject ? $sitemap->updatedAtField : "[$sitemap->updatedAtField]";

		return $accessor->getValue($result, $property);
	}

	/**
	 * @throws DOMException
	 * @throws DateMalformedStringException
	 * @throws Exception|InvalidArgumentException
	 */
	public function sitemap (string $name = 'index'): SitemapFile
	{
		$routes = $this->router->getAllRoutes();

		if ('index' === $name) {
			return $this->getIndex($routes);
		} elseif ('default' === $name) {
			return $this->getDefault($routes);
		}

		return $this->getPage($name, $routes);
	}

	/**
	 * @param array<string, RouteAttributes> $routes
	 *
	 * @throws DOMException
	 * @internal
	 */
	private function getPage (string $name, array $routes): SitemapFile
	{
		$sitemap = new SitemapFile($name);

		foreach ($routes as $routeName => $route) {
			if (!$route->attributes->offsetExists(SitemapAttribute::class)) {
				continue;
			}

			/** @var SitemapAttribute[] $objSitemaps */
			$objSitemaps = $route->attributes->offsetGet(SitemapAttribute::class)->getArrayCopy();
			$objSitemap = array_find($objSitemaps, fn($objSitemap) => $objSitemap?->name === $sitemap->getBaseName());

			if (!$objSitemap) {
				continue;
			}

			try {
				$repository = $this->entityManager->getRepository($objSitemap->entity);

				if (is_string($objSitemap->criteria)) {
					$results = $repository->{$objSitemap->criteria}();
				} else {
					// For now only 50k results
					$results = $repository->matching($objSitemap->criteria->setMaxResults(50_000));
				}

				foreach ($results as $result) {
					$params = RouterGenerateSeoUrl::processUrlParameters($objSitemap, $result);
					$url = $objSitemap->getUrl($this->router->generateUrl($routeName, $params));
					$url->setLastMod(self::processLastUpdated($result, $objSitemap));

					$sitemap->addUrl($url);
				}

				$sitemap->updateAtField();
			} catch (Exception|InvalidArgumentException) {
			}
		}

		return $sitemap;
	}

	/**
	 * @param array<string, RouteAttributes> $routes
	 *
	 * @throws DateMalformedStringException
	 * @throws Exception
	 * @internal
	 */
	private function getIndex (array $routes): SitemapFile
	{
		$sitemap = new SitemapFile('index');

		$url = $this->router->generateUrl('idm_seo_sitemap_file', ['name' => 'default']);
		$sitemap->addSitemap(new Sitemap($url, new DateTime()));

		foreach ($routes as $route) {
			if (!$route->attributes->offsetExists(SitemapAttribute::class)) {
				continue;
			}
			/** @var SitemapAttribute[] $objSitemaps */
			$objSitemaps = $route->attributes->offsetGet(SitemapAttribute::class);

			foreach ($objSitemaps as $objSitemap) {
				if ($objSitemap->isDynamic()) {
					$url = $this->router->generateUrl('idm_seo_sitemap_file', ['name' => $objSitemap->name]);
					$sitemap->addSitemap(new Sitemap($url, new DateTime()));
				}
			}
		}

		return $sitemap;
	}

	/**
	 * @param array<string, RouteAttributes> $routes
	 *
	 * @throws Exception
	 * @internal
	 */
	private function getDefault (array $routes): SitemapFile
	{
		$sitemap = new SitemapFile('default');

		foreach ($routes as $routeName => $route) {
			if (!$route->attributes->offsetExists(SitemapAttribute::class)) {
				continue;
			}

			/** @var SitemapAttribute $objSitemap */
			$objSitemap = $route->attributes->offsetGet(SitemapAttribute::class)->offsetGet(0);

			if (!$objSitemap->isDynamic()) {
				$sitemap->addUrl($objSitemap->getUrl($this->router->generateUrl($routeName)));
			}
		}

		return $sitemap;
	}

}
