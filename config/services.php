<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 19:04
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

use Idm\Bundle\Seo\Cache\Warmer\InvalidateSeoCache;
use Idm\Bundle\Seo\Controller\SitemapController;
use Idm\Bundle\Seo\EventSubscriber\SeoConfigureSubscriber;
use Idm\Bundle\Seo\Form\Type\OpenGraph\SeoLocaleType;
use Idm\Bundle\Seo\Service\BreadcrumbBuilder;
use Idm\Bundle\Seo\Service\RouterGenerateSeoUrl;
use Idm\Bundle\Seo\Service\SeoPage;
use Idm\Bundle\Seo\Service\SeoPageInterface;
use Idm\Bundle\Seo\Service\SitemapGenerator;
use Idm\Bundle\Seo\Twig\Extension\SeoExtension;
use Idm\Bundle\Seo\Twig\Runtime\SeoRuntime;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return function (ContainerConfigurator $container) {
	// @formatter:off
	$container->services()
		// Services
		->set('idm_seo.service.router_generator_seo_url', RouterGenerateSeoUrl::class)
			->private()
			->arg('$router', service('router.default'))
			->arg('$cache', service('idm_seo.cache'))
			->arg('$denormalizer', service('serializer'))
		->set('idm_seo.service.cache_adapter', FilesystemAdapter::class)
			->private()
			->args(['', '0', '%kernel.cache_dir%/pools/seo', service('cache.default_marshaller')])
		->set('idm_seo.service.sitemap_generator', SitemapGenerator::class)
			->private()
			->args([
				'$router' => service('idm_seo.service.router_generator_seo_url'),
				'$entityManager' => service('doctrine.orm.entity_manager'),
			])
		->set('imd_seo.service.breadcrumb_builder', BreadcrumbBuilder::class)
			->private()
			->args([
				'$router' => service('router.default'),
				'$requestStack' => service('request_stack'),
				'$translator' => service('translator')->nullOnInvalid(),
			])
		->set('idm_seo.service.seo_page', SeoPage::class)
			->private()
			->arg('$router', service('idm_seo.service.router_generator_seo_url'))
			->alias(SeoPageInterface::class, 'idm_seo.service.seo_page')->public()

		->set(InvalidateSeoCache::class)
			->private()
			->arg('$cache', service('idm_seo.cache'))
			->tag('kernel.cache_warmer')

		// Controllers
		->set(SitemapController::class)
			->private()
			->arg('$generator', service('idm_seo.service.sitemap_generator'))
			->call('setContainer', [service_locator([])])
			->tag('controller.service_arguments')

		// Events Subscriber
		->set(SeoConfigureSubscriber::class)
			->private()
			->arg('$seo', service('idm_seo.service.seo_page'))
			->arg('$translator', service('translator')->nullOnInvalid())
			->tag('kernel.event_subscriber')

		// Forms
		->set(SeoLocaleType::class)
			->private()
			->args(['$enabledLocales' => param('kernel.enabled_locales')])
			->tag('form.type')

		// Twig Extensions
		->set('idm_seo.twig.extension.seo', SeoExtension::class)
			->private()
			->tag('twig.extension')
		->set('idm_seo.twig.extension.seo.runtime', SeoRuntime::class)
			->private()
			->args([
				'$seoPage' => service('idm_seo.service.seo_page')
			])
			->tag('twig.runtime')
	;
	// @formatter::on

	$container->import(__DIR__.'/services/easyadmin.php');
};
