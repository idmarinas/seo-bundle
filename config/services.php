<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/11/2025, 16:22
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    services.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Idm\Bundle\Seo\Cache\Warmer\GenerateSitemap;
use Idm\Bundle\Seo\Command\SeoSitemapGenerateCommand;
use Idm\Bundle\Seo\Controller\SitemapController;
use Idm\Bundle\Seo\Service\SeoPage;
use Idm\Bundle\Seo\Service\Sitemap\SitemapGenerator;
use Idm\Bundle\Seo\Twig\Extension\SeoExtension;
use Idm\Bundle\Seo\Twig\Runtime\SeoRuntime;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return function (ContainerConfigurator $container) {
	// @formatter:off
	$container->services()
		->set('idm_seo.service.cache_adapter', FilesystemAdapter::class)
			->private()
			->args(['', '0', '%kernel.cache_dir%/pools/seo', service('cache.default_marshaller')])

		->set('idm_seo.service.sitemap_generator', SitemapGenerator::class)
			->private()
			->args([
				'$router' => service('router.default'),
				'$cache' => service('idm_seo.cache'),
				'$entityManager' => service('doctrine.orm.entity_manager'),
				'$defaultScheme' => param('idm_seo.parameter.sitemap.default_scheme'),
				'$excludedRoutes' => param('idm_seo.parameter.sitemap.excluded_routes'),
			])

		->set('idm_seo.cache.warmer', GenerateSitemap::class)
			->private()
			->args(['$generator' => service('idm_seo.service.sitemap_generator')])
			->tag('kernel.cache_warmer')

		->set(SitemapController::class, SitemapController::class)
			->private()
			->call('setContainer', [service_locator([
				'idm_seo.cache' => service('idm_seo.cache'),
			])])
			->tag('controller.service_arguments')

		->set('idm_seo.command.generate_sitemap', SeoSitemapGenerateCommand::class)
			->private()
			->args(['$generator' => service('idm_seo.service.sitemap_generator')])
			->tag('console.command')

		->set('idm_seo.service.seo_page', SeoPage::class)
			->private()
			->args([
				'$config' => param('idm_seo.parameter.seo'),
			])
		->alias(SeoPage::class, 'idm_seo.service.seo_page')->public()

		->set('idm_seo.twig.extension.seo', SeoExtension::class)
			->private()
			->tag('twig.extension')

		->set('idm_seo.twig.extension.seo.runtime', SeoRuntime::class)
			->private()
			->args([
				'$templates' => param('idm_seo.parameter.seo.title.templates'),
			])
			->tag('twig.runtime')
	;
	// @formatter::on
};
