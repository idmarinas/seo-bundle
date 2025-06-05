<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 04/06/2025, 21:40
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Url.php
 * @date    28/05/2025
 * @time    19:53
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap\Node;

use DateMalformedStringException;
use DateTimeInterface;
use DOMDocument;
use DOMElement;
use DOMException;
use Idm\Bundle\Seo\Attributes\Sitemap\SitemapInterface;
use InvalidArgumentException;

final class Url extends AbstractNode
{
	/**
	 * @throws DateMalformedStringException
	 */
	public function __construct (
		protected string                        $loc,
		protected null|string|DateTimeInterface $lastmod = null,
		protected ?string                       $changefreq = null,
		protected null|float|int|string         $priority = null
	) {
		parent::__construct($loc, $lastmod);
		$this->setChangefreq($changefreq);
		$this->setPriority($priority);
	}

	public function setChangefreq (?string $changefreq): self
	{
		if (!in_array($changefreq, SitemapInterface::CHANGEFREQ_VALUES)) {
			throw new InvalidArgumentException(
				sprintf(
					'The value "%s" is not supported by the option changefreq.'
					. ' See https://www.sitemaps.org/protocol.html#xmlTagDefinitions',
					$changefreq
				)
			);
		}

		$this->changefreq = $changefreq;

		return $this;
	}

	public function getChangefreq (): ?string
	{
		return $this->changefreq;
	}

	public function setPriority (null|float|int|string $priority = null): self
	{
		if (null === $priority) {
			return $this;
		}

		if (is_string($priority) || is_int($priority)) {
			$priority = (float)$priority;
		}

		if ($priority < 0 || $priority > 1) {
			throw new InvalidArgumentException(
				sprintf(
					'The value "%s" is not supported by the option priority.'
					. ' See https://www.sitemaps.org/protocol.html#xmlTagDefinitions',
					$priority
				)
			);
		}

		$this->priority = round($priority, 1);

		return $this;
	}

	public function getPriority (): ?string
	{
		return $this->priority;
	}

	/**
	 * @inheritdoc
	 * @throws DOMException
	 */
	public function getNode (DOMDocument $document): DOMElement
	{
		$node = $document->createElement('url');

		$node->appendChild($document->createElement('loc', $this->getLoc()));

		if (null !== $this->getLastmod()) {
			$node->appendChild($document->createElement('lastmod', $this->getLastmod()));
		}

		if (null !== $this->getChangefreq()) {
			$node->appendChild($document->createElement('changefreq', $this->getChangefreq()));
		}

		if (null !== $this->getPriority()) {
			$node->appendChild($document->createElement('priority', $this->getPriority()));
		}

		return $node;
	}
}
