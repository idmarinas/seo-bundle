<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/06/2025, 17:47
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

namespace Idm\Bundle\Seo\Service;

use ArrayObject;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use DOMException;
use Exception;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapDynamic;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapInterface;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapUrl;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Idm\Bundle\Seo\Traits\Service\SaveLoadTrait;
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
	use SaveLoadTrait;

	/** @var ArrayObject<string, SitemapFile> */
	private ArrayObject $sitemapList;

	public function __construct (
		private readonly RouterInterface                               $router,
		private readonly CacheItemPoolInterface&TagAwareCacheInterface $cache,
		private readonly EntityManagerInterface                        $entityManager,
	) {
		$this->sitemapList = new ArrayObject();
	}

	public static function getCacheKey (string $name): string
	{
		return 'idm_seo_sitemap_' . $name;
	}

	/**
	 * @throws DOMException
	 * @throws DateMalformedStringException
	 * @throws InvalidArgumentException
	 * @throws CacheException
	 * @throws Exception
	 */
	public function generate (): void
	{
		$ignore = ['_preview_error', '_profiler', '_wdt', '_debug'];
		$collection = $this->router->getRouteCollection()->all();
		$routers = array_filter($collection, fn(string $r) => !u($r)->startsWith($ignore), ARRAY_FILTER_USE_KEY);

		$sitemapIndex = $this->getCachedSitemap('index', true);
		$sitemapDefault = $this->getCachedSitemap('default');

		$url = $this->generateUrl('idm_seo_sitemap_file', ['name' => 'default']);
		$sitemapIndex->getDocument()->addSitemap(new Sitemap($url, new DateTime()));

		foreach ($routers as $routeName => $route) {
			if (null === $sitemap = $this->getSitemapFromRoute($route)) {
				continue;
			}

			if ($sitemap instanceof SitemapUrl) {
				// Add URL to Default Sitemap
				$sitemapDefault->getDocument()->addUrl($sitemap->getUrl($this->generateUrl($routeName)));
			} elseif ($sitemap instanceof SitemapDynamic) {
				// Add URL to NAMED Sitemap
				$url = $this->generateUrl('idm_seo_sitemap_file', ['name' => $sitemap->name]);
				$sitemapIndex->getDocument()->addSitemap(new Sitemap($url, new DateTime()));
				if (null !== $sitemapFile = $this->generateSitemapDynamic($sitemap, $routeName)) {
					$this->save($sitemap->name, $sitemapFile);
				}
			}
		}

		$this->save('index', $sitemapIndex);
		$this->save('default', $sitemapDefault);
	}

	private function getSitemapFromRoute (Route $route): ?SitemapInterface
	{
		$controller = u((string)$route->getDefault('_controller'))->trim();

		if ($controller->isEmpty()) {
			return null;
		}

		$template = 'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController';

		return match (true) {
			$controller->containsAny('::')      => $this->getSitemapFromAttribute($controller),
			$controller->containsAny($template) => $this->getSitemapFromTemplate($route),
			default                             => null,
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

			/** @var SitemapInterface $instance */
			$instance = $attributes[0]->newInstance();

			return $instance;
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
