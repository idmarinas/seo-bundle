<?php
/**
 * Copyright 2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/04/2026, 20:35
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    easyadmin.php
 * @date    05/04/2026
 * @time    20:35
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use EasyCorp\Bundle\EasyAdminBundle\DependencyInjection\EasyAdminExtension;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Idm\Bundle\Seo\Admin\Action\SeoActionExtension;
use Idm\Bundle\Seo\Admin\Field\Configurator\OpenGraphTypeDataConfigurator;
use Idm\Bundle\Seo\Controller\Admin\OpenGraphCrudController;
use Idm\Bundle\Seo\Controller\Admin\SeoCrudController;
use Idm\Bundle\Seo\Controller\Admin\TwitterCardCrudController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

return function (ContainerConfigurator $container, ContainerBuilder $builder) {
	if (!$builder->hasExtension('easy_admin')) {
		return;
	}
	$crudServiceLocator = service_locator([
		AdminContextProvider::class => service(AdminContextProvider::class)->nullOnInvalid(),
		'parameter_bag'             => service(ContainerBagInterface::class)->nullOnInvalid(),
	]);

	// @formatter:off
	$container->services()
		// Admin
		->set(OpenGraphTypeDataConfigurator::class)
			->private()
			->tag(EasyAdminExtension::TAG_FIELD_CONFIGURATOR, ['priority' => -100])
		->set(SeoActionExtension::class)
			->private()
			->arg('$translator', service('translator'))
			->tag(EasyAdminExtension::TAG_ACTIONS_EXTENSION)


		// Admin Crud Controllers
		->set(SeoCrudController::class)
			->private()
			->call('setContainer', [$crudServiceLocator])
			->tag('controller.service_arguments')
		->set(OpenGraphCrudController::class)
			->private()
			->call('setContainer', [$crudServiceLocator])
			->tag('controller.service_arguments')
		->set(TwitterCardCrudController::class)
			->private()
			->call('setContainer', [$crudServiceLocator])
			->tag('controller.service_arguments')
	;
};
