<?php
/**
 * Copyright 2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/01/2026, 17:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    DashboardController.php
 * @date    05/01/2026
 * @time    17:00
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace App\Controller\Admin;

use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Override;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
final class DashboardController extends AbstractDashboardController
{
	#[Override]
    public function index (): Response
	{
		return $this->render('pages/dashboard.html.twig');
	}

	#[Override]
    public function configureDashboard (): Dashboard
	{
		return Dashboard::new()
			->setTitle('Html')
		;
	}

	#[Override]
	public function configureActions (): Actions
	{
		return parent::configureActions()->add(Crud::PAGE_INDEX, Action::DETAIL);
	}

	#[Override]
    public function configureMenuItems (): iterable
	{
		yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
		yield MenuItem::linkToCrud('Item', 'fas fa-list', Item::class);
//		yield MenuItem::linkToCrud('Seo', 'fas fa-hashtag', Seo::class);
//		yield MenuItem::linkToCrud('OpenGraph', 'fas fa-hashtag', OpenGraph::class);
//		yield MenuItem::linkToCrud('TwitterCard', 'fas fa-hashtag', TwitterCard::class);
		// yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
	}
}
