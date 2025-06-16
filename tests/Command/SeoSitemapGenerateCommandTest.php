<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 16/06/2025, 16:47
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoSitemapGenerateCommandTest.php
 * @date    16/06/2025
 * @time    16:32
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SeoSitemapGenerateCommandTest extends KernelTestCase
{
	public function testGenerate ()
	{
		self::bootKernel();
		$app = new Application(self::$kernel);
		$command = $app->find('idm:seo:sitemap:generate');

		$cmdTester = new CommandTester($command);
		$cmdTester->execute(['-i' => true]);

		$cmdTester->assertCommandIsSuccessful();

		$output = $cmdTester->getDisplay();
		$this->assertStringContainsString('Sitemap generated successfully!', $output);
	}

	public function testUpdate ()
	{
		self::bootKernel();
		$app = new Application(self::$kernel);
		$command = $app->find('idm:seo:sitemap:generate');

		$cmdTester = new CommandTester($command);
		$cmdTester->execute([]);

		$cmdTester->assertCommandIsSuccessful();

		$output = $cmdTester->getDisplay();
		$this->assertStringContainsString('Sitemap updated successfully!', $output);
	}
}
