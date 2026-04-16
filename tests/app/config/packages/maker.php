<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 19/03/2025, 18:06
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    maker.php
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

return static function (ContainerConfigurator $container): void {
	$container->extension('maker', [
		'root_namespace' => (new ReflectionClass(IdmSeoBundle::class))->getNamespaceName(),
	]);
};
