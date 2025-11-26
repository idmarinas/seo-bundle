<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 26/11/2025, 16:04
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    routes.php
 * @date    26/11/2025
 * @time    16:03
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use App\Controller\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminRouteLoader;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
	// @formatter:off
	$routes->import('routes/web_profiler.php');

	//$routes->import('security.route_loader.logout', 'service')->methods(['GET']);

	$routes
		->import(DashboardController::class, AdminRouteLoader::ROUTE_LOADER_TYPE)
	;

//	$routes
//		->add('app_home', '/')
//		->methods(['GET'])
//		->controller(TemplateController::class)
//		->defaults([
//			'template' => 'pages/home.html.twig',
//		])
//	;
	// @formatter:on
};
