<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 02/12/2025, 21:04
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    open_graph.php
 * @date    11/11/2025
 * @time    14:30
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use Idm\Bundle\Seo\Entity\OpenGraph;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

return (function (): NodeDefinition {
	//@formatter:off
	$treeBuilder = new TreeBuilder('open_graph');

	return $treeBuilder->getRootNode()
		->info('Default configuration for Open Graph tags.')
		->addDefaultsIfNotSet()
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
		->end();
})();
