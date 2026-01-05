<?php
/**
 * Copyright 2025-2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/01/2026, 17:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    HomeController.php
 * @date    26/11/2025
 * @time    16:05
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace App\Controller;

use App\Entity\Item;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Idm\Bundle\Seo\Attributes\Seo;
use Idm\Bundle\Seo\Attributes\Sitemap;
use Idm\Bundle\Seo\Attributes\Sitemap\Prop;
use Idm\Bundle\Seo\Attributes\SitemapInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
//	#[Route('/', name: 'app_home')]
//	#[Sitemap]
//	#[Seo]
	public function home (): Response
	{
		return $this->render('pages/home.html.twig');
	}

	#[Route('/item/{id}', name: 'app_item')]
	#[Sitemap(
		name         : 'item',
		entity       : Item::class,
		criteria     : 'findAll',
		urlParameters: [
			'id'   => new Prop('id'),
			'code' => 'code-slug',
		]
	)]
	#[Sitemap(
		name         : 'item_expired',
		priority     : 0.2,
		changefreq   : SitemapInterface::CHANGEFREQ_NEVER,
		entity       : Item::class,
		criteria     : new Criteria(
//			expression: new CompositeExpression(CompositeExpression::TYPE_AND, [
//				new Comparison('category', Comparison::EQ, 5),
//				new Comparison('published', Comparison::EQ, true),
//			]),
			orderings: ['title' => Order::Descending]
		),
		urlParameters: [
			'id' => new Prop('id'),
		]
	)]
	#[Seo(entity: Item::class)]
	public function item (Item $item): Response
	{
		return $this->render('pages/home.html.twig', ['item' => $item]);
	}

	#[Route('/seo', name: 'app_seo')]
	#[Sitemap]
	#[Seo]
	public function seo (): Response
	{
		return $this->render('pages/home.html.twig');
	}
}
