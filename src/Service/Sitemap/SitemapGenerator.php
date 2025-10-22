<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 22/10/2025, 15:25
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapGenerator.php
 * @date    26/05/2025
 * @time    20:50
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service\Sitemap;

use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapDynamic;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapInterface;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapUrl;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Traits\Service\CacheSaveAndLoadTrait;
use Idm\Bundle\Seo\Traits\Service\SitemapGenerator\GenerateDynamicSitemapTrait;
use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use function Symfony\Component\String\u;

final class SitemapGenerator
{
	use GenerateDynamicSitemapTrait;
	use CacheSaveAndLoadTrait;

	public function __construct (
		private readonly RouterInterface                               $router,
		private readonly CacheItemPoolInterface&TagAwareCacheInterface $cache,
		private readonly EntityManagerInterface                        $entityManager,
		private readonly string                                        $defaultScheme,
		private readonly array                                         $excludedRoutes,
	) {
		$this->router->getContext()->setScheme($this->defaultScheme);
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws InvalidArgumentException
	 * @throws CacheException
	 * @throws Exception
	 */
	public function generate (bool $invalidate = false): void
	{
		$all = $this->router->getRouteCollection()->all();
		$routers = array_filter($all, fn(string $r) => !u($r)->startsWith($this->excludedRoutes), ARRAY_FILTER_USE_KEY);

		$sitemapIndex = $this->getCachedSitemap('index', $invalidate);
		$sitemapDefault = $this->getCachedSitemap('default', $invalidate);

		$url = $this->generateUrl('idm_seo_sitemap_file', ['name' => 'default']);
		$sitemapIndex->addSitemap(new Sitemap($url, new DateTime()));

		foreach ($routers as $routeName => $route) {
			if (null === $sitemap = $this->getSitemapFromRoute($route)) {
				continue;
			}

			if ($sitemap instanceof SitemapUrl) {
				// Add URL to Default Sitemap
				$sitemapDefault->addUrl($sitemap->getUrl($this->generateUrl($routeName)));
			} elseif ($sitemap instanceof SitemapDynamic) {
				// Add URL to NAMED Sitemap
				$url = $this->generateUrl('idm_seo_sitemap_file', ['name' => $sitemap->name]);
				$sitemapIndex->addSitemap(new Sitemap($url, new DateTime()));

				if (null !== $sitemapFile = $this->generateSitemapDynamic($sitemap, $routeName, $invalidate)) {
					$this->prepareToSave($sitemap->name, $sitemapFile);
				}
			}
		}

		$this->save('index', $sitemapIndex);
		$this->prepareToSave('default', $sitemapDefault);
	}

	private function getSitemapFromRoute (Route $route): ?SitemapInterface
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
			return new SitemapUrl(changefreq: SitemapInterface::CHANGEFREQ_YEARLY);
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

	/** @private */
	private function generateUrl (string $name, array $parameters = []): string
	{
		return $this->router->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
	}
}
