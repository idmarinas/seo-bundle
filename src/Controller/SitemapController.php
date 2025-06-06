<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/06/2025, 17:18
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

use Idm\Bundle\Seo\Service\SitemapHandler;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class SitemapController extends AbstractController
{
	public function __construct (private readonly SitemapHandler $handler) {}

	/**
	 * @throws InvalidArgumentException
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
		$sitemap = $this->handler->getRootSitemap();

		return new Response($sitemap->getDocument()->toString());
	}

	/**
	 * @throws InvalidArgumentException
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
		$sitemap = $this->handler->getSitemap($name);

		return new Response($sitemap->getDocument()->toString());
	}

	/**
	 * @throws InvalidArgumentException
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
		$sitemap = $this->handler->getSitemapPage($name, $id);

		return new Response($sitemap->getDocument()->toString());
	}
}
