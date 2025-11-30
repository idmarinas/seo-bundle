<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 30/11/2025, 19:34
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    GenerateDynamicSitemapTrait.php
 * @date    04/06/2025
 * @time    19:28
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Service\SitemapGenerator;

use Exception;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapDynamic;
use Idm\Bundle\Seo\Service\RouterGenerateSeoUrl;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use Psr\Cache\InvalidArgumentException;

trait GenerateDynamicSitemapTrait
{
	protected static function processLastUpdated (array|object $result, SitemapDynamic $sitemap): mixed
	{
		$isObject = $result instanceof $sitemap->entity;

		if ($isObject) {
			$method = 'get' . ucfirst($sitemap->updatedAtField);

			return method_exists($sitemap->entity, $method) ? $result->{$method}() : null;
		}

		return $result[$sitemap->updatedAtField] ?? null;
	}

	private function generateSitemapDynamic (SitemapDynamic $sitemap, string $routeName, bool $invalidate): ?SitemapFile
	{
		try {
			$repository = $this->entityManager->getRepository($sitemap->entity);

			if (is_string($sitemap->criteria)) {
				$results = $repository->{$sitemap->criteria}();
			} else {
				$results = $repository->matching($sitemap->criteria);
			}

			$sitemapFile = $this->getCachedSitemap($sitemap->name, invalidate: $invalidate);

			foreach ($results as $result) {
				$params = RouterGenerateSeoUrl::processUrlParameters($sitemap, $result);
				$url = $sitemap->getUrl($this->router->generateUrl($routeName, $params));
				$url->setLastMod(self::processLastUpdated($result, $sitemap));

				$sitemapFile->addUrl($url);
			}

			$sitemapFile->updateAtField();

			return $sitemapFile;
		} catch (Exception|InvalidArgumentException) {
			return null;
		}
	}
}
