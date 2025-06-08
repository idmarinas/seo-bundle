<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 08/06/2025, 23:01
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OptimizeTrait.php
 * @date    08/06/2025
 * @time    21:41
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Sitemap\SitemapFile;

use DateMalformedStringException;
use DateTime;
use DOMException;
use Exception;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Sitemap\Node\Url;
use Idm\Bundle\Seo\Sitemap\SitemapFile;
use LogicException;

trait OptimizeTrait
{
	/**
	 * Checks if the sitemap meets the requirements for item limit and size
	 * and splits it into multiple sitemaps with their indexes if necessary.
	 *
	 * This method optimizes the sitemap to ensure it complies with search engine requirements:
	 * - Maximum 50,000 URLs per sitemap
	 * - Maximum 50MB (52,428,800 bytes) per sitemap file
	 *
	 * @param callable $generateUrl Callback to generate URLs for sitemap references
	 *                              Should accept sitemap name and parameters and return a full URL
	 *
	 * @return array<string, SitemapFile> Returns an array with the generated sitemaps:
	 *                                      <code>
	 *                                        // If not split, returns
	 *                                        [
	 *                                          'fileName' => 'this sitemap'
	 *                                        ]
	 *                                      </code>
	 *
	 *                                      <code>
	 *                                        // If split, returns
	 *                                        [
	 *                                          'fileName.0' => 'sitemap1',
	 *                                          'fileName.1' => 'sitemap2',
	 *                                          //...
	 *                                          'fileName' => 'sitemapIndex'
	 *                                        ]
	 *                                      </code>
	 *
	 * @throws LogicException If there is an error while splitting the sitemap
	 * @throws DOMException|DateMalformedStringException If there is an error creating the DOM
	 * @throws Exception
	 */
	public function optimize (callable $generateUrl): array
	{
		// If it's an index sitemap, it cannot be split
		if ($this->isIndex()) {
			throw new LogicException('Cannot split an index sitemap.');
		}

		// If it doesn't exceed the limits, return the original sitemap
		if ($this->isValid()) {
			return [$this->getName() => $this];
		}

		// Create an index sitemap
		$indexSitemap = new SitemapFile($this->getName(), true);

		// Prepare the result
		$result = [];
		$result[$this->getName()] = $indexSitemap;

		// Get all URL nodes from the current sitemap
		$urls = [];
		$nodes = $this->document->getElementsByTagName('url');

		// Convert nodes to Url objects for manipulation
		for ($i = 0; $i < $nodes->length; $i++) {
			$node = $nodes->item($i);

			// Extract information from the node
			$loc = $node->getElementsByTagName('loc')->item(0)?->textContent ?? '';
			$lastmod = $node->getElementsByTagName('lastmod')->item(0)?->textContent ?? null;
			$changefreq = $node->getElementsByTagName('changefreq')->item(0)?->textContent ?? null;
			$priority = $node->getElementsByTagName('priority')->item(0)?->textContent ?? null;

			if (!empty($loc)) {
				// Create a Url object with the extracted information
				$urls[] = new Url($loc, $lastmod, $changefreq, $priority);
			}
		}

		// Split URLs into multiple sitemaps
		$urlsChunks = array_chunk($urls, self::LIMIT_ITEMS - 1);

		// Create the partial sitemaps
		foreach ($urlsChunks as $id => $urlsChunk) {
			$partName = sprintf('%s.%d', $this->getName(), $id);
			$partSitemap = new SitemapFile($partName);

			// Add URLs to the partial sitemap
			foreach ($urlsChunk as $url) {
				$partSitemap->addUrl($url);
			}
//
//			// Verify that the partial sitemap size doesn't exceed the limit
//			if (strlen($partSitemap->toString()) > self::LIMIT_BYTES) {
//				throw new LogicException(
//					sprintf(
//						'Cannot split the sitemap into smaller parts. The size of partial sitemap %s exceeds the 50MB limit.',
//						$partName
//					)
//				);
//			}

			// Add the partial sitemap to the result
			$result[$partName] = $partSitemap;

			// Add reference to the partial sitemap in the index
			$indexSitemap->addSitemap(new Sitemap($generateUrl($this->getName(), ['id' => $id]), new DateTime()));
		}

		return $result;
	}
}
