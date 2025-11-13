<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 12/11/2025, 13:28
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoEntityInterface.php
 * @date    10/11/2025
 * @time    19:39
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity;

interface SeoEntityInterface
{
	public function getSeo (): ?Seo;

	public function setSeo (?Seo $seo): static;
}
