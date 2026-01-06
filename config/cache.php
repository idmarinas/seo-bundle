<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 26/05/2025, 20:48
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    cache.php
 * @date    02/01/2025
 * @time    23:07
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
	$container->extension('framework', [
		'cache' => [
			'pools' => [
				'idm_seo.cache' => [
					'adapter' => 'idm_seo.service.cache_adapter',
					'tags'    => true,
				],
			],
		],
	]);
};
