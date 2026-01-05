<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 04/11/2025, 19:30
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    doctrine.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Idm\Bundle\Seo\IdmSeoBundle;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use function Symfony\Component\String\u;

return static function (ContainerConfigurator $container, ContainerBuilder $builder) {
	$getDatabaseCache = function (string $projectDir, string $env): string {
		$dir = $projectDir . '/var/cache/database';

		$filesystem = new Filesystem();

		if (!$filesystem->exists($dir)) {
			$filesystem->mkdir($dir);
		}

		$dbName = (new ReflectionClass(IdmSeoBundle::class))->getShortName();
		$dbName = u($dbName)->snake()->toString();

		return sprintf('sqlite:///%s/%s_%s.sqlite', $dir, $dbName, $env);
	};

	$container->extension('doctrine', [
		'dbal' => [
			'driver' => 'pdo_sqlite',
			'url'    => $getDatabaseCache($builder->getParameter('kernel.project_dir'), $container->env()),
		],
		'orm'  => [
			'auto_mapping'        => true,
			'controller_resolver' => [
				'auto_mapping' => false,
			],
			'mappings'            => [
				'Tests' => [
					'is_bundle' => false,
					'mapping'   => true,
					'type'      => 'attribute',
					'dir'       => dirname(__DIR__, 2) . '/src/Entity',
					'prefix'    => 'App\Entity',
				],
			],
			//'resolve_target_entities' => [
			//	AbstractUser::class => User::class,
			//],
		],
	]);
};
