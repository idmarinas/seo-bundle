<?php

/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 13/03/2025, 22:13
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    BundleInitializationTest.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Tests;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BundleInitializationTest extends KernelTestCase
{
	use CreateKernelCaseTrait;

	public function testInitBundle (): void
	{
		// Boot the kernel.
		$kernel = self::bootKernel([
			'config' => static function (Kernel $kernel) {
//				$kernel->addExtraBundle(BundleName::class);
//				$kernel->addExtraConfig('path/to/file.php');
//				$kernel->addExtraConfig(['extension_name' => ['key_1' => 'value_1']);
//				$kernel->addExtraRoutesFile('path/to/file.php');
			},
		]);

		$this->assertTrue($kernel->getContainer()->has('kernel'));
	}
}
