<?php
/**
 * Copyright 2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 21/04/2026, 13:34
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    breadcrumb.php
 * @date    21/04/2026
 * @time    13:34
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return function (DefinitionConfigurator $definition): void {
	// @formatter:off
	$definition->rootNode()
		->children()
			->arrayNode('breadcrumb')
				->info('Configuration for the breadcrumb component.')
				->addDefaultsIfNotSet()
				->children()
					->arrayNode('home')
						->info('Configuration for the home breadcrumb item.')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('label')
								->info('Label for the home breadcrumb item.')
								->cannotBeEmpty()
								->defaultValue('Home')
							->end()
							->scalarNode('icon')
								->info('Icon name for the home breadcrumb item (UX Icons).')
								->defaultValue('tabler:home')
							->end()
							->scalarNode('route')
								->info('Route name for the home breadcrumb item.')
								->cannotBeEmpty()
								->defaultValue('app_home')
						->end()
					->end()
				->end()
			->end()
		->end()
	->end()
	;
	// @formatter:on
};
