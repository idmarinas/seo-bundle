<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 07/03/2025, 18:42
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    framework.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config) {
	$config
		->secret('test')
		->test(true)
		->httpMethodOverride(false)
		->handleAllThrowables(true)
	;
	$config->form()->enabled(false);
	$config->propertyAccess()->enabled(true);
	$config->phpErrors()->log(true);
};
