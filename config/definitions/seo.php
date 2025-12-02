<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/12/2025, 17:26
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

use Idm\Bundle\Seo\Entity\OpenGraph;
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
					->scalarNode('description')
						->info('Default description if none is set.')
						->defaultValue('')
						->validate()
							->ifTrue(static fn ($v) => mb_strlen($v) > 160)
							->thenInvalid('The description must be less than 160 characters long.')
							->end()
					->end()
					->arrayNode('open_graph')
						->addDefaultsIfNotSet()
						->info('Default configuration for Open Graph tags.')
						->children()
							->scalarNode('site_name')
								->info('The name which should be displayed for the overall site.')
								->defaultValue('IDMarinas Seo Bundle')
							->end()
							->enumNode('type')
								->info('The default type of your object as defined by [Open Graph protocol](https://ogp.me/#types).')
								->values(OpenGraph::TYPE_ALL)
								->defaultValue('website')
							->end()
						->end()
					->end()
					->arrayNode('twitter')
						->addDefaultsIfNotSet()
						->info('Default configuration for Twitter Card tags.')
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
						->end()
					->end()
				->end()
			->end()
		->end()
	;
};
