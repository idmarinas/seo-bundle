<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 16:40
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file IdmSeoBundle.php
 * @date 19/03/2025
 * @time 17:06
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo;

use Idm\Bundle\Seo\DependencyInjection\Compiler\CheckAttributesValidityPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class IdmSeoBundle extends AbstractBundle
{
	public function loadExtension (array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->import(dirname(__DIR__) . '/config/services.php');

		$services = $container->services();

		// Add enabled locales to filter with supported locales
		$config['seo']['enabled_locales'] = $builder->getParameter('kernel.enabled_locales');

		$services
			->get('idm_seo.service.seo_page')
			->arg('$config', $config['seo'])
		;

		$services
			->get('idm_seo.service.sitemap_generator')
			->arg('$defaultScheme', $config['sitemap']['default_scheme'])
		;

		$services
			->get('idm_seo.service.router_generator_seo_url')
			->arg('$excludedRoutes', $config['sitemap']['excluded_routes'])
		;
	}

	public function prependExtension (ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->import(dirname(__DIR__) . '/config/cache.php');
	}

	public function build (ContainerBuilder $container): void
	{
		$container->addCompilerPass(new CheckAttributesValidityPass());
	}

	public function configure (DefinitionConfigurator $definition): void
	{
		$definition->import(dirname(__DIR__) . '/config/definitions.php');

		parent::configure($definition);
	}
}
