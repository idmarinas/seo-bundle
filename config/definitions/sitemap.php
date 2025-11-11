<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 11/11/2025, 15:32
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    sitemap.php
 * @date    11/11/2025
 * @time    14:19
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return function (DefinitionConfigurator $definition): void {
	$defaultExcludedRoutes = ['_preview_error', '_profiler', '_wdt', '_debug', '_error', 'admin_'];

	// @formatter:off
	$definition->rootNode()
		->children()
			->arrayNode('sitemap')
				->addDefaultsIfNotSet()
				->children()
					->enumNode('default_scheme')
						->info('Default scheme to use for URLs.')
						->defaultValue('https')
						->values(['http', 'https'])
					->end()
					->arrayNode('excluded_routes')
						->info('List of route names or route name patterns to exclude from sitemap. You can exclude specific routes by their exact name or use patterns like "admin_" to exclude all routes starting with that prefix. This helps optimize sitemap generation.')
						->arrayPrototype()->end()
						->defaultValue($defaultExcludedRoutes)
						->beforeNormalization()
							->castToArray()
							->always(static fn(array $v) => array_unique(($v + $defaultExcludedRoutes)))
						->end()
					->end()
				->end()
			->end()
		->end()
	;
	// @formatter:on
};
