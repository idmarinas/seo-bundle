<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 13/06/2025, 16:58
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapController.php
 * @date    26/05/2025
 * @time    20:50
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Controller;

use Idm\Bundle\Seo\Cache\CacheKeyEnum;
use Idm\Bundle\Seo\Cache\CacheTagEnum;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Contracts\Cache\ItemInterface;

final class SitemapController extends AbstractController
{
	public static function getSubscribedServices (): array
	{
		return [
			'cache' => '?idm_seo.cache',
		];
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	#[Route('/sitemap.{!_format}',
		name        : 'idm_seo_sitemap_index',
		requirements: ['_format' => 'xml'],
		defaults    : ['_format' => 'xml'],
		methods     : ['GET'],
		format      : 'xml'
	)]
	#[Cache(maxage: 3600, public: true)]
	public function index (): Response
	{
		$sitemap = $this->cache('index');

		return new Response($sitemap->toString());
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	#[Route('/sitemap/{name}.{!_format}',
		name        : 'idm_seo_sitemap_file',
		requirements: [
			'name'    => '[a-zA-Z]+',
			'_format' => 'xml',
		],
		defaults    : ['_format' => 'xml'],
		methods     : ['GET'],
		format      : 'xml'
	)]
	#[Cache(maxage: 3600, public: true)]
	public function sitemap (string $name): Response
	{
		$sitemap = $this->cache($name);

		if ($sitemap->isEmpty()) {
			throw $this->createNotFoundException(sprintf('Sitemap "%s.xml" not found.', $name));
		}

		return new Response($sitemap->toString());
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	#[Route('/sitemap/{name}.{!id}.{!_format}',
		name        : 'idm_seo_sitemap_file_page',
		requirements: [
			'name'    => '[a-zA-Z]+',
			'id'      => Requirement::DIGITS,
			'_format' => 'xml',
		],
		defaults    : ['id' => 0, '_format' => 'xml'],
		methods     : ['GET'],
		format      : 'xml'
	)]
	#[Cache(maxage: 3600, public: true)]
	public function sitemapPage (string $name, int $id): Response
	{
		$sitemap = $this->cache($name . '.' . $id);

		if ($sitemap->isEmpty()) {
			throw $this->createNotFoundException(sprintf('Sitemap "%s.%d.xml" not found.', $name, $id));
		}

		return new Response($sitemap->toString());
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	protected function cache (string $name): SitemapFile
	{
		if (!$this->container->has('idm_seo.cache')) {
			throw new LogicException(sprintf('You cannot use the "%s" method if the Cache is not available.', __METHOD__));
		}

		$name = 'root' === $name ? 'index' : $name;
		$key = CacheKeyEnum::SITEMAP->suffix($name);

		return $this->container->get('idm_seo.cache')->get($key, function (ItemInterface $item) use ($name) {
			$item->tag(CacheTagEnum::SITEMAP->value);

			return new SitemapFile($name);
		});
	}
}
