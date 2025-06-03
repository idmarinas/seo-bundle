<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/06/2025, 20:29
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

namespace Idm\Bundle\Seo\Sitemap;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;
use DOMDocument;
use DOMElement;
use DOMException;
use InvalidArgumentException;

final class Url
{
	public const CHANGEFREQ_VALUES = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never', null];

	/**
	 * @throws DateMalformedStringException
	 */
	public function __construct (
		private string                        $loc,
		private null|string|DateTimeInterface $lastmod = null,
		private ?string                       $changefreq = null,
		private null|float|int|string         $priority = null
	) {
		$this->setLoc($loc);
		$this->setLastmod($lastmod);
		$this->setChangefreq($changefreq);
		$this->setPriority($priority);
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

	public function setChangefreq (?string $changefreq): self
	{
		if (!in_array($changefreq, self::CHANGEFREQ_VALUES)) {
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
