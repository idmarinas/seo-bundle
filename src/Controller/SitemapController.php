<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 19:09
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file SitemapController.php
 * @date 26/05/2025
 * @time 20:50
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Controller;

use Exception;
use Idm\Bundle\Seo\Service\SitemapGenerator;
use Psr\Cache\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class SitemapController extends AbstractController
{
	public function __construct (private readonly SitemapGenerator $generator) {}

	/**
	 * @throws ContainerExceptionInterface
	 */
	#[Route('/sitemap.{!_format}',
		name        : 'idm_seo_sitemap_index',
		requirements: ['_format' => 'xml'],
		defaults    : ['_format' => 'xml'],
		methods     : ['GET'],
		format      : 'xml',
		stateless   : true
	)]
	#[Cache(maxage: 86400, public: true, mustRevalidate: true)]
	public function index (): Response
	{
		try {
			$sitemap = $this->generator->sitemap();
		} catch (Exception|InvalidArgumentException) {
			throw $this->createNotFoundException('Sitemap "sitemap.xml" not found.');
		}

		return (new Response($sitemap->toString()))
			->setLastModified($sitemap->getUpdatedAt())
		;
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	#[Route('/sitemap/{name}.{!_format}',
		name        : 'idm_seo_sitemap_file',
		requirements: [
			'name'    => '[a-zA-Z_]+',
			'_format' => 'xml',
		],
		defaults    : ['_format' => 'xml'],
		methods     : ['GET'],
		format      : 'xml',
		stateless   : true
	)]
	#[Cache(maxage: 36000, public: true, mustRevalidate: true)]
	public function sitemap (string $name): Response
	{
		try {
			$sitemap = $this->generator->sitemap($name);
		} catch (Exception|InvalidArgumentException) {
			throw $this->createNotFoundException(sprintf('The sitemap for "%s.xml" could not be generated.', $name));
		}

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
		format      : 'xml',
		stateless   : true
	)]
	#[Cache(maxage: 36000, public: true, mustRevalidate: true)]
	public function sitemapPage (string $name, int $id): Response
	{
		try {
			$sitemap = $this->generator->sitemap($name . '.' . $id);
		} catch (Exception|InvalidArgumentException) {
			throw $this->createNotFoundException(sprintf('The sitemap for "%s.%d.xml" could not be generated.', $name, $id));
		}

		if ($sitemap->isEmpty()) {
			throw $this->createNotFoundException(sprintf('Sitemap "%s.%d.xml" not found.', $name, $id));
		}

		return new Response($sitemap->toString());
	}
}
