<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 04/06/2025, 21:50
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Sitemap.php
 * @date    04/06/2025
 * @time    21:30
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Sitemap\Node;

use DOMDocument;
use DOMElement;
use DOMException;

final class Sitemap extends AbstractNode
{
	/** @inheritdoc
	 * @throws DOMException
	 */
	public function getNode (DOMDocument $document): DOMElement
	{
		$node = $document->createElement('sitemap');

		$node->appendChild($document->createElement('loc', $this->getLoc()));

		if (null !== $this->getLastmod()) {
			$node->appendChild($document->createElement('lastmod', $this->getLastmod()));
		}

		return $node;
	}
}
