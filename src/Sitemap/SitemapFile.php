<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/10/2025, 17:07
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SitemapFile.php
 * @date    28/05/2025
 * @time    19:52
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap;

use Countable;
use DateTime;
use DateTimeInterface;
use DOMDocument;
use DOMElement;
use DOMException;
use DOMXPath;
use Exception;
use Idm\Bundle\Seo\Sitemap\Node\AbstractNode;
use Idm\Bundle\Seo\Sitemap\Node\Sitemap;
use Idm\Bundle\Seo\Sitemap\Node\Url;
use Idm\Bundle\Seo\Traits\Sitemap\SitemapFile\OptimizeTrait;
use InvalidArgumentException;
use LogicException;
use function Symfony\Component\String\u;

/**
 * Allows generating both standard sitemaps and sitemap indexes
 */
final class SitemapFile implements Countable
{
	use OptimizeTrait;

	public const int LIMIT_ITEMS = 50_000;
	public const int LIMIT_BYTES = 52_428_800; // 50MB

	private DOMDocument       $document;
	private ?DOMElement       $rootElement = null;
	private DateTimeInterface $updatedAt;

	/**
	 * Creates a new sitemap instance
	 *
	 * @param string    $name  The name of the sitemap file
	 * @param null|bool $index Whether this is an index sitemap (true) or a normal sitemap (false)
	 *
	 * @throws DOMException If there's an error creating the DOM structure
	 */
	public function __construct (private string $name, private ?bool $index = null)
	{
		$this->index = $index ?? ('index' === $name || u($name)->endsWith('.index'));
		$this->document = new DOMDocument('1.0', 'UTF-8');
		$this->document->formatOutput = true;

		$this->updateAtField();

		// Initialize the root element according to the sitemap type
		$this->initRootElement($this->index ? 'sitemapindex' : 'urlset');
	}

	/**
	 * Adds a URL node to the sitemap
	 *
	 * @param Url $url The URL node to add
	 *
	 * @throws LogicException If this is an index sitemap
	 * @throws Exception If there's an error adding the node
	 */
	public function addUrl (Url $url): self
	{
		if ($this->index) {
			throw new LogicException('Cannot add URL node to index sitemap. Use addSitemap() instead.');
		}

		return $this->addNode($url);
	}

	/**
	 * Adds a Sitemap node to the sitemap index
	 *
	 * @param Sitemap $sitemap The Sitemap node to add
	 *
	 * @throws LogicException If this is not an index sitemap
	 * @throws Exception If there's an error adding the node
	 */
	public function addSitemap (Sitemap $sitemap): self
	{
		if (!$this->index) {
			throw new LogicException('Cannot add sitemap node to non-index sitemap. Use addUrl() instead.');
		}

		return $this->addNode($sitemap);
	}

	/**
	 * Returns the number of URL or Sitemap nodes in the sitemap
	 */
	public function count (): int
	{
		return $this->document->getElementsByTagName($this->index ? 'sitemap' : 'url')->count();
	}

	/**
	 * Returns the sitemap XML as a string
	 */
	public function toString (): string
	{
		return $this->document->saveXML();
	}

	/**
	 * Validates that the sitemap complies with specifications
	 *
	 * @throws LogicException
	 */
	public function isValid (): bool
	{
		// Check that it does not exceed the maximum number of URLs
		if ($this->count() > self::LIMIT_ITEMS) {
			return false;
		}

		// Check that the XML size does not exceed 50 MB
		if (mb_strlen($this->toString(), '8bit') > self::LIMIT_BYTES) { // 50MB in bytes
			return false;
		}

		return true;
	}

	/**
	 * Method for compatibility with related classes' code
	 */
	public function isIndex (): bool
	{
		return $this->index;
	}

	/**
	 * Method for compatibility with related classes' code
	 */
	public function getName (): string
	{
		return $this->name;
	}

	/**
	 * Checks if a sitemap is empty
	 */
	public function isEmpty (): bool
	{
		return $this->count() === 0;
	}

	public function getUpdatedAt (): DateTimeInterface
	{
		return $this->updatedAt;
	}

	public function updateAtField (): self
	{
		$this->updatedAt = new DateTime();

		return $this;
	}

	/**
	 * Loads an XML into the sitemap
	 *
	 * @param string $xml The XML to load
	 *
	 * @throws InvalidArgumentException If the XML is invalid
	 */
	public function load (string $xml): void
	{
		try {
			$result = $this->document->loadXML($xml);

			if ($result === false) {
				throw new InvalidArgumentException('Failed to load XML: Invalid XML format');
			}

			$this->document->encoding = 'UTF-8';
			$this->document->formatOutput = true;

			// Validar que el XML tenga la estructura correcta
			$hasIndex = $this->document->getElementsByTagName('sitemapindex')->length > 0;
			$hasUrlset = $this->document->getElementsByTagName('urlset')->length > 0;

			if ($hasIndex && $hasUrlset) {
				throw new InvalidArgumentException('XML contains both sitemapindex and urlset elements');
			}

			if (!$hasIndex && !$hasUrlset) {
				throw new InvalidArgumentException('XML does not contain valid sitemap structure');
			}

			if ($hasIndex !== $this->index) {
				throw new InvalidArgumentException(
					sprintf(
						'XML type mismatch: expected %s but got %s',
						$this->index ? 'sitemapindex' : 'urlset',
						$hasIndex ? 'sitemapindex' : 'urlset'
					)
				);
			}

			// Update the root element reference
			$rootTagName = $this->index ? 'sitemapindex' : 'urlset';
			$this->rootElement = $this->document->getElementsByTagName($rootTagName)->item(0);
		} catch (Exception $e) {
			throw new InvalidArgumentException('Failed to load XML: ' . $e->getMessage(), 0, $e);
		}
	}

	public function __serialize (): array
	{
		$this->updateAtField();

		return [
			'updated_at' => $this->getUpdatedAt(),
			'sitemap'    => $this->toString(),
			'name'       => $this->getName(),
			'index'      => $this->isIndex(),
		];
	}

	public function __unserialize (array $data): void
	{
		$this->updatedAt = $data['updated_at'];
		$this->name = $data['name'];
		$this->index = $data['index'];

		$this->document = new DOMDocument('1.0', 'UTF-8');
		$this->load($data['sitemap']);
	}

	/**
	 * Initializes the root element of the sitemap
	 *
	 * @param string $rootElementName Root element name ('urlset' or 'sitemapindex')
	 *
	 * @throws DOMException If there's an error creating the element
	 */
	private function initRootElement (string $rootElementName): void
	{
		$this->rootElement = $this->document->createElementNS(
			'https://www.sitemaps.org/schemas/sitemap/0.9',
			$rootElementName
		);
		$this->document->appendChild($this->rootElement);
	}

	/**
	 * Adds a node to the sitemap (URL or Sitemap)
	 *
	 * @param AbstractNode $node The node to add
	 *
	 * @throws LogicException If the root element is not initialized
	 * @throws Exception If there's an error creating the node
	 */
	private function addNode (AbstractNode $node): self
	{
		try {
			$newNode = $node->getNode($this->document);
			// Search and remove all duplicates URL already
			$this->removeUrlsByLocation($node->getLoc());

			$this->rootElement->appendChild($newNode);

			return $this;
		} catch (Exception $e) {
			throw new Exception(
				sprintf('Error adding node with location "%s": %s', $node->getLoc(), $e->getMessage()),
				$e->getCode(),
				$e
			);
		}
	}

	/**
	 * Remove all duplicate URLs
	 *
	 * @param string $location The URL to search for
	 */
	private function removeUrlsByLocation (string $location): void
	{
		$xpath = new DOMXPath($this->document);

		// Registrar el namespace del sitemap
		$xpath->registerNamespace('sm', 'https://www.sitemaps.org/schemas/sitemap/0.9');

		$tag = $this->index ? 'sitemap' : 'url';
		$query = sprintf("//sm:%s[sm:loc/text()='%s']", $tag, str_replace(["'", '"'], ["''", '""'], $location));

		$nodes = $xpath->query($query);

		if ($nodes === false || $nodes->length === 0) {
			return;
		}

		// Eliminar todos los nodos encontrados
		foreach ($nodes as $node) {
			$node->parentNode->removeChild($node);
		}
	}
}
