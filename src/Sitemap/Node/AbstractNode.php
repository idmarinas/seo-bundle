<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 04/06/2025, 21:39
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AbstractNode.php
 * @date    04/06/2025
 * @time    21:35
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap\Node;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;
use DOMDocument;
use DOMElement;
use InvalidArgumentException;

abstract class AbstractNode
{
	/**
	 * @throws DateMalformedStringException
	 */
	public function __construct (protected string $loc, protected null|string|DateTimeInterface $lastmod = null)
	{
		$this->setLoc($loc);
		$this->setLastmod($lastmod);
	}

	public function setLoc (string $loc): self
	{
		if (strlen($loc) >= 2048) {
			throw new InvalidArgumentException(
				sprintf(
					'The URL "%s" length must be less than 2,048 characters.'
					. ' See https://www.sitemaps.org/protocol.html#xmlTagDefinitions',
					$loc
				)
			);
		}

		if (!str_starts_with($loc, 'http')) {
			throw new InvalidArgumentException(
				sprintf(
					'The URL "%s" must begin with the protocol (http or https).'
					. ' See https://www.sitemaps.org/protocol.html#xmlTagDefinitions',
					$loc
				)
			);
		}

		$this->loc = $loc;

		return $this;
	}

	public function getLoc (): string
	{
		return $this->loc;
	}

	/**
	 * @throws DateMalformedStringException
	 */
	public function setLastmod (null|string|DateTimeInterface $lastmod): self
	{
		if (is_string($lastmod)) {
			$lastmod = new DateTime($lastmod);
		}

		$this->lastmod = $lastmod;

		return $this;
	}

	public function getLastmod (): ?string
	{
		return $this->lastmod?->format(DateTimeInterface::W3C);
	}

	/**
	 * Generates a DOMElement node using the provided DOMDocument.
	 *
	 * @param DOMDocument $document The DOMDocument instance to use for creating the node.
	 *
	 * @return DOMElement The generated DOMElement node.
	 */
	abstract public function getNode (DOMDocument $document): DOMElement;
}
