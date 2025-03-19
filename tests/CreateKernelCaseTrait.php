<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 14/03/2025, 23:20
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    CreateKernelCaseTrait.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   2.0.0
 */

namespace Idm\Bundle\Seo\Tests;

use App\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

trait CreateKernelCaseTrait
{
	protected static function createKernel (array $options = []): KernelInterface
	{
		/** @var Kernel $kernel */
		$kernel = parent::createKernel($options);
		$kernel->handleOptions($options);

		return $kernel;
	}
}
