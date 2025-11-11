<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/11/2025, 16:45
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
	$config->csrfProtection()->enabled(true);
	$config->session()->enabled(true)->cookieSecure(true)->handlerId(null)->cookieSamesite('lax');
	$config->form()->enabled(true)->csrfProtection()->enabled(true);
	$config->propertyAccess()->enabled(true);
	$config->phpErrors()->log(true);
	$config->assets()->enabled(true);

	$config->validation()->enabled(true)->emailValidationMode('html5')->notCompromisedPassword()->enabled(false);

	$config->defaultLocale('en')->enabledLocales(['en', 'es']);
};
