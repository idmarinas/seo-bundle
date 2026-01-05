<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/12/2025, 12:05
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    twitter.php
 * @date    11/11/2025
 * @time    14:30
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

return (function (): NodeDefinition {
	//@formatter:off
	$treeBuilder = new TreeBuilder('twitter');

	return $treeBuilder->getRootNode()
		->info('Default configuration for Twitter Card tags.')
		->addDefaultsIfNotSet()
		->children()
			->enumNode('card')
				->info('The type of card to display.')
				->values(['summary', 'summary_large_image', 'app', 'player'])
				->defaultValue('summary')
			->end()
			->scalarNode('site')
				->info('The Twitter site name. It must start with "@".')
				->defaultValue('')
				->validate()
					->ifTrue(static fn ($v) => !str_starts_with($v, '@'))
					->thenInvalid('The Twitter site name must start with "@".')
				->end()
			->end()
			->scalarNode('creator')
				->info('The Twitter creator\'s name. It must start with "@".')
				->defaultValue('')
				->validate()
					->ifTrue(static fn ($v) => !str_starts_with($v, '@'))
					->thenInvalid('The Twitter creator name must start with "@".')
				->end()
			->end()
		->end();
})();
