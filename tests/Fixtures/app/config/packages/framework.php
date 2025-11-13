<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 13/11/2025, 14:33
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
		->defaultLocale('en')
		->enabledLocales(['en', 'es'])
		->httpMethodOverride(false)
		->handleAllThrowables(true)
		->phpErrors()->log(true)
	;

	$config->csrfProtection()->enabled(true);
	$config->propertyAccess()->enabled(true);

	// Session Config
	$config->session()->enabled(true)->cookieSecure(true)->handlerId(null)->cookieSamesite('lax');

	// Form Config
	$config->form()->enabled(true)->csrfProtection()->enabled(true);

	// Assets config
	$config->assets()->enabled(true);

	// Validation Config
	$config->validation()->enabled(true)->emailValidationMode('html5')->notCompromisedPassword()->enabled(false);

	// UID config
	$config->uid()->enabled(true)->defaultUuidVersion(7)->timeBasedUuidVersion(7);

	// Mailer config
	$mailer = $config->mailer()->enabled(false);
	$mailer->dsn(getenv('MAILER_DSN') ?? 'null://null');
	$mailer->envelope()->sender('idm_bundle@test.bundle');
	$mailer->header('From', 'IDMarinas Seo Bundle <idm_bundle@test.bundle>');

	// Router config
	$config->router()->enabled(true)->utf8(true);

	// Messenger config
	$messenger = $config->messenger()->enabled(false);
	$messenger->transport('sync', 'in-memory://');
	$messenger->routing('Symfony\\Component\\Mailer\\Messenger\\SendEmailMessage')->senders(['sync']);
};
