<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 30/11/2025, 19:34
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
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapUrl;
use Idm\Bundle\Seo\Service\RouterGenerateSeoUrl;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Traits\Service\CacheSaveAndLoadTrait;
use Idm\Bundle\Seo\Traits\Service\SitemapGenerator\GenerateDynamicSitemapTrait;
use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use function Symfony\Component\String\u;

final class SitemapGenerator
{
	use GenerateDynamicSitemapTrait;
	use CacheSaveAndLoadTrait;

	public function __construct (
		private readonly RouterGenerateSeoUrl                          $router,
		private readonly CacheItemPoolInterface&TagAwareCacheInterface $cache,
		private readonly EntityManagerInterface                        $entityManager,
		private readonly string                                        $defaultScheme,
		private readonly array                                         $excludedRoutes,
	) {
		$this->router->setScheme($this->defaultScheme);
	}

	/**
	 * @throws DateMalformedStringException
	 * @throws InvalidArgumentException
	 * @throws CacheException
	 * @throws Exception
	 */
	public function generate (bool $invalidate = false): void
	{
		$all = $this->router->getAllRoutes();
		$routers = array_filter($all, fn(string $r) => !u($r)->startsWith($this->excludedRoutes), ARRAY_FILTER_USE_KEY);

		$sitemapIndex = $this->getCachedSitemap('index', $invalidate);
		$sitemapDefault = $this->getCachedSitemap('default', $invalidate);

		$url = $this->router->generateUrl('idm_seo_sitemap_file', ['name' => 'default']);
		$sitemapIndex->addSitemap(new Sitemap($url, new DateTime()));

		foreach ($routers as $routeName => $route) {
			if (null === $sitemap = $this->router->getSitemapFromRoute($route)) {
				continue;
			}

			if ($sitemap instanceof SitemapUrl) {
				// Add URL to Default Sitemap
				$sitemapDefault->addUrl($sitemap->getUrl($this->router->generateUrl($routeName)));
			} elseif ($sitemap instanceof SitemapDynamic) {
				// Add URL to NAMED Sitemap
				$url = $this->router->generateUrl('idm_seo_sitemap_file', ['name' => $sitemap->name]);
				$sitemapIndex->addSitemap(new Sitemap($url, new DateTime()));

				if (null !== $sitemapFile = $this->generateSitemapDynamic($sitemap, $routeName, $invalidate)) {
					$this->prepareToSave($sitemap->name, $sitemapFile);
				}
			}
		}

		$this->save('index', $sitemapIndex);
		$this->prepareToSave('default', $sitemapDefault);
	}
}
