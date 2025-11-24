<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 19:16
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoCrudControllerTrait.php
 * @date    10/11/2025
 * @time    12:31
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Idm\Bundle\Seo\Entity\Seo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use function Idm\Bundle\Seo\t;

trait SeoCrudControllerTrait
{
	#[AdminRoute('/{entityId}/add-seo')]
	public function addSeoToEntity (
		AdminContext           $context,
		EntityManagerInterface $entityManager,
		AdminUrlGenerator      $urlGenerator
	):
	RedirectResponse {
		$entity = $context->getEntity()->getInstance();

		$url = $urlGenerator
			->setController($context->getCrud()->getControllerFqcn())
			->setAction(Action::EDIT)
			->setEntityId($entity->getId())
			->generateUrl()
		;

		if ($entity->getSeo() != null) {
			return $this->redirect($url);
		}

		$this->fillSeoData($entity);

		$entityManager->flush();

		$this->addFlash('success', t('admin.flash.success.added'));

		return $this->redirect($url);
	}

	#[AdminRoute('/{entityId}/remove-seo')]
	public function removeSeoOfEntity (
		AdminContext           $context,
		EntityManagerInterface $entityManager,
		AdminUrlGenerator      $urlGenerator
	): RedirectResponse {
		$entity = $context->getEntity()->getInstance();

		$url = $urlGenerator
			->setController($context->getCrud()->getControllerFqcn())
			->setAction(Action::EDIT)
			->setEntityId($entity->getId())
			->generateUrl()
		;

		$entity->setSeo(null);

		$entityManager->flush();

		$this->addFlash('success', t('admin.flash.success.deleted'));

		return $this->redirect($url);
	}

	private function fillSeoData (object $entity): void
	{
		$seo = new Seo();

		if (method_exists($entity, 'getTitle')) {
			$seo->meta->title = $entity->getTitle();
		}

		$entity->setSeo($seo);
	}
}
