<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/06/2025, 18:34
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    definitions.php
 * @date    04/06/2025
 * @time    13:40
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return function (DefinitionConfigurator $definition): void {
	// @formatter:off
	$definition
		->rootNode()
			->children()
				->enumNode('default_scheme')
					->defaultValue('https')
					->values(['http', 'https'])
					->info('Default scheme to use for URLs.')
				->end()
			->end()
		->end()
	;
	// @formatter:on
};
