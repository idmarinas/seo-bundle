<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 19:18
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

return static function (ContainerConfigurator $container) {
	$container->extension('framework', [
		'secret'                => 'test',
		'http_method_override'  => false,
		'test'                  => true,
		'default_locale'        => 'en',
		'enabled_locales'       => ['en', 'es'],
		'handle_all_throwables' => true,
		'csrf_protection'       => [
			'enabled' => true,
		],
		'form'                  => [
			'enabled'         => true,
			'csrf_protection' => [
				'enabled' => true,
			],
		],
		'http_cache'            => [
			'enabled' => false,
			'debug'   => true,
		],
		'router'                => [
			'enabled' => true,
			'utf8'    => true,
		],
		'session'               => [
			'enabled'         => true,
			'handler_id'      => null,
			'cookie_secure'   => true,
			'cookie_samesite' => 'lax',
		],
		'assets'                => [
			'enabled' => true,
		],
		'validation'            => [
			'enabled'                  => true,
			'email_validation_mode'    => 'html5',
			'not_compromised_password' => [
				'enabled' => false,
			],
		],
		'property_access'       => [
			'enabled' => true,
		],
		'php_errors'            => [
			'log' => true,
		],
		'messenger'             => [
			'enabled'    => true,
			'routing'    => [
				'Symfony\Component\Mailer\Messenger\SendEmailMessage' => [
					'senders' => [
						0 => 'sync',
					],
				],
			],
			'transports' => [
				'sync' => 'in-memory://',
			],
		],
		'mailer'                => [
			'enabled'  => true,
			'dsn'      => getenv('MAILER_DSN') ?? 'null://null',
			'envelope' => [
				'sender' => 'idm_bundle@test.bundle',
			],
			'headers'  => [
				'From' => 'IDMarinas Seo Bundle <idm_bundle@test.bundle>',
			],
		],
		'uid'                   => [
			'enabled'                 => true,
			'default_uuid_version'    => 7,
			'time_based_uuid_version' => 7,
		],

	]);
};
