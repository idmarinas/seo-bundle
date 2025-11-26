<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 26/11/2025, 16:49
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    seo.php
 * @date    11/11/2025
 * @time    14:30
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return function (DefinitionConfigurator $definition): void {
	$defaultTemplates = [
		'title' => '{title} {separator} {suffix}',
		'page'  => '{prefix} {separator} {title} {separator} {suffix}',

	];
	//@formatter:off
	$definition->rootNode()
		->children()
			->arrayNode('seo')
				->addDefaultsIfNotSet()
				->children()
					// Título de la página
					->arrayNode('title')
						->info('Configuration for the title of the page.')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('default')->cannotBeEmpty()->defaultValue('IDMarinas Seo Bundle')->end()
							->scalarNode('prefix')->defaultValue('')->end()
							->scalarNode('separator')->defaultValue('|')->end()
							->scalarNode('suffix')->defaultValue('')->end()
							// Formatting templates for the title
							->arrayNode('templates')
								->info('Formatting templates for the title. Placeholders: {title}, {separator}, {prefix}, {suffix}.')
								->fixXmlConfig('template')
								->useAttributeAsKey('name')
								->beforeNormalization()
									->always(fn ($v) => array_merge($defaultTemplates, $v))
								->end()
								->defaultValue($defaultTemplates)
								->scalarPrototype()
									->validate()
										->ifTrue(static fn ($v) => !str_contains($v, '{title}'))
										->thenInvalid('The template must contain the "{title}" placeholder.')
									->end()
								->end()
							->end()
						->end()
					->end()
					->arrayNode('open_graph')
						->info('Default configuration for Open Graph tags.')
						->children()
							->scalarNode('site_name')
								->info('The name which should be displayed for the overall site.')
								->defaultValue('IDMarinas Seo Bundle')
							->end()
							->scalarNode('type')
							->end()
						->end()
					->end()
					->arrayNode('twitter')
						->info('Default configuration for Twitter Card tags.')
						->children()
							->scalarNode('site')
								->info('The Twitter site name. It must start with "@".')
								->defaultValue('')
								->validate()
									->ifTrue(static fn ($v) => !str_starts_with($v, '@'))
									->thenInvalid('The Twitter site name must start with "@".')
							->end()
						->end()
					->end()
				->end()
			->end()
		->end()
	;
};
