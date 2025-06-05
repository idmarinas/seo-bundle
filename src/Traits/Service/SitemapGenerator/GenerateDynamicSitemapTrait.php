<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/06/2025, 18:59
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

use DateTime;
use Exception;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapDynamic;
use Psr\Cache\InvalidArgumentException;

trait GenerateDynamicSitemapTrait
{

	private function generateSitemapDynamic (SitemapDynamic $sitemap, string $name): void
	{
		try {
			$repository = $this->entityManager->getRepository($sitemap->entity);

			if (is_string($sitemap->criteria)) {
				$results = $repository->{$sitemap->criteria}();
			} else {
				$results = $repository->matching($sitemap->criteria);
			}

			$sitemapFile = $this->getCachedSitemap($name);
			$sitemapFile->setUpdatedAt(new DateTime());

			$parameters = function (array $params, array|object $result, bool $isObject) use ($sitemap): array {
				$params = array_filter($params, fn($v) => $isObject ? method_exists($sitemap->entity, $v) : isset($result[$v]));
				array_walk($params, function (&$item, $key) {});

				return $params;
			};

			$getLastMod = function (bool $isObject, array|object $result) use ($sitemap): mixed {
				if ($isObject) {
					$method = 'get' . ucfirst($sitemap->updatedAtField);

					return method_exists($sitemap->entity, $method) ? $result->{$method}() : null;
				}

				return $result[$sitemap->updatedAtField] ?? null;
			};

			foreach ($results as $result) {
				$isObject = $result instanceof $sitemap->entity;
				$url = $sitemap->getUrl($this->generateUrl($name, $parameters($sitemap->urlParameters, $result, $isObject)));
				$url->setLastMod($getLastMod($isObject, $result));

				$sitemapFile->getDocument()->addUrl($url);
			}
		} catch (Exception|InvalidArgumentException) {
		}
	}

}
